<?php

namespace App\Services\Admin\Usermanagement;

use App\Http\Traits\FileManagementTrait;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserPlaylistService
{
    use FileManagementTrait;

    public function getUserPlaylists($orderBy = 'sort_order', $order = 'asc')
    {
        return Playlist::orderBy($orderBy, $order)->latest();
    }
    public function getUserPlaylist(string $encryptedId): Playlist | Collection
    {
        return Playlist::findOrFail(decrypt($encryptedId));
    }
    public function getDeletedUserPlaylist(string $encryptedId): Playlist | Collection
    {
        return Playlist::onlyTrashed()->findOrFail(decrypt($encryptedId));
    }

    public function createUser(array $data, $file = null): Playlist
    {
        return DB::transaction(function () use ($data, $file) {
           
            $data['creater_id'] = admin()->id;
            $user = Playlist::create($data);
            return $user;
        });
    }

    public function updateUser(Playlist $user, array $data, $file = null): Playlist
    {
        return DB::transaction(function () use ($user, $data, $file) {
         
            $data['updater_id'] = admin()->id;
            $data['updater_type'] = get_class(admin());
            $user->update($data);
            return $user;
        });
    }

    public function delete(Playlist $user): void
    {
        $user->update(['deleter_id' => admin()->id, 'deleter_type' => get_class(admin())]);
        $user->delete();
    }

    public function restore(string $encryptedId): void
    {
        $user = $this->getDeletedUserPlaylist($encryptedId);
        $user->update(['updater_id' => admin()->id],
            ['updater_type' => get_class(admin())]);
        $user->restore();
    }

    public function permanentDelete(string $encryptedId): void
    {
        $user = $this->getDeletedUserPlaylist($encryptedId);
        $user->forceDelete();
    }

    public function toggleStatus(Playlist $user): void
    {
        $user->update([
            'status' => !$user->status,
            'updater_id' => admin()->id,
            'updater_type' => get_class(admin())
        ]);
    }
}