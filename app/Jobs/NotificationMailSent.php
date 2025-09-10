<?php

namespace App\Jobs;

use App\Mail\NotificationMails;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationMailSent implements ShouldQueue
{
    use Queueable;

    public $mailDatas = [];


    /**
     * Create a new job instance.
     */
    public function __construct($mailDatas)
    {
        $this->mailDatas = $mailDatas;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->mailDatas as $mailData) {
            Log::info('Mail Data:', $mailData);
            Mail::to($mailData['email'])->send(new NotificationMails($mailData));
        }
    }
}
