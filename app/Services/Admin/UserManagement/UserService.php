<?php

    namespace App\Services\Admin\UserManagement;
    use App\Models\User;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;      
    
    class UserService
    {
        public function createUser(array $data, $file = null): User
        {
            return DB::transaction(function () use ($data, $file) {
                $data['creater_id'] = user()->id;
                $data['creater_type'] = get_class(user());
                $user = User::create($data);
                return $user;                
            });        
        } 
         public function getDeletedUser(string $encryptedId): User | Collection
        {
            return User::onlyTrashed()->findOrFail(decrypt($encryptedId));
        }

        public function updateUser(User $user, array $data, $file = null): User
            {
                return DB::transaction(function () use ($user, $data, $file) {
                    $data['password'] = $data['password'] ?? $user->password;
                    $data['updated_by'] = user()->id;           
                    $user->update($data);
                    $user->syncRoles($user->role->name);
                    return $user; 
                });
            }
                 
         public function delete (User $user):  void
         {
            $user->update(['deleted_by' => user()->id]);
            $user->delete();
         }

         public function restore(string $encryptedId): void
         {
            $user = $this->getDeletedUser($encryptedId);
            $user->update(['updated_by' => user()->id]);
            $user->restore();
         }

         public function permanentDelete(string $encryptedId): void

         {
            $user = $this->getDeletedUser($encryptedId);
            $user->forceDelete();
         }
         
         public function toggleStatus(User $user): void
            {
              $user->update([
                'status' => !$user->status,
                'updated_by' => user()->id
              ]);
        }
 }  




