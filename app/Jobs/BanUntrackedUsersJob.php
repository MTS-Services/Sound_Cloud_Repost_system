<?php

namespace App\Jobs;

use App\Mail\NotificationMails;
use App\Models\User;
use App\Services\SoundCloud\SoundCloudService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BanUntrackedUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected SoundCloudService $soundCloudService;

    public function __construct(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }

    public function handle(): void
    {
        Log::info('Starting BanUntrackedUsersJob...');
        $users = User::whereNull('banned_at')
            ->where('status', User::STATUS_ACTIVE)
            ->get();

        $bannedCount = 0;

        $firstUser = $users->first();

        if (!$firstUser) {
            Log::warning('No active unbanned users found. Job exiting. and the users : ' . json_encode($users));
            return;
        }

        Log::info("Found " . $users->count() . " active unbanned users to check.");

        foreach ($users as $user) {
            $trackCount = $this->soundCloudService->soundCloudRealTracksCount($user, $firstUser);
            if ($trackCount === 0) {
                Log::info("User {$user->urn} has no SoundCloud tracks. Banning user.");
                $user->update([
                    'banned_at' => now(),
                    'bander_id' => null,
                    'ban_reason' => 'Your linked SoundCloud account has no public tracks. RepostChain is intended for active creators, so accounts without tracks may be temporarily suspended.',
                    'status' => User::STATUS_INACTIVE,
                ]);
                $mailDatas = [[
                    'email' => $user->email,
                    'subject' => 'Your RepostChain account is temporarily suspended',
                    'title' => 'Hi ' . $user->name . ',',
                    'body' => 'We’ve suspended your RepostChain account ' . $user->name . ' for a potential violation of our Community Guidelines.',
                    'ban_reason' => 'Your linked SoundCloud account has no public tracks. RepostChain is intended for active creators, so accounts without tracks may be temporarily suspended.',
                    'guideline_link' => route('f.terms-and-conditions'),
                    'unban' => false,
                ]];
                foreach ($mailDatas as $mailData) {
                    Mail::to($mailData['email'])->send(new NotificationMails($mailData));
                }
                $bannedCount++;
            }
        }

        Log::info("{$bannedCount} users banned because they had no SoundCloud tracks.");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('BanUntrackedUsersJob failed: ' . $exception->getMessage());
    }
}
