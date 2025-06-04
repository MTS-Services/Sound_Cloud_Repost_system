<?php 


namespace App\Services\Admin\UserManagement;

use League\Fractal\Resource\Collection;
    use App\Models\User; 
    use GuzzleHttp\Psr7\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\DB;
    use App\Http\Traits\FileManagementTrait;
    use Yajra\DataTables\Facades\DataTables;

    class UserService
    {    
        // public function createAdmin(array $data, $file = null): User
        // {
        //     return DB::transaction(function () use ($data, $file) {
        //         $data['creater_id'] = user()->id;
        //         $data['creater_type'] = get_class(user());
        //         $user = User::create($data);
        //         return $user;
        //     });
                     
        // }          
               
        // public function updateUser(User $user, array $data, $file = null): User
        // {
        //     return DB::transaction(function () use ($user, $file){
        //         $data['password'] = $data['password'] ?? $user->password;
        //         $data['updated_by'] = user()->id;
        //         $user->update($data);
        //         $user->syncRoles($user->role->name);
        //         return $user;
        //     });
        // }  
        
        // public function getDeletedUser(string $encryptedId): User
        // {
        //     return User::onlyTrashed()->findOrFail(decrypt($encryptedId));
        // }
        
        // public function delete(User $user): void 
        // {
        //     $user->update(['deleted_by' => user()->id]);
        //     $user->delete();
        // }
 
        // public function restore(string $encryptedId): void
        // {
        //     $user = $this->getDeletedUser($encryptedId);
        //     $user->update(['updated_by' => user()->id]);
        //     $user->restore();
        // }   

        // public function permanentDelete(string $encryptedId): void
        // {
        //     $user = $this->getDeletedUser($encryptedId);
        //     $user->forceDelete();
        // }

        // public function toggleStatus(User $user): void
        // {
        //     $user->update([
        //         'status' => !$user->status,
        //         'updated_by' => user()->id
        //     ]);
        // }     

        use FileManagementTrait;

        public function getUsers($orderBy = 'name', $order = 'asc')
        {
            return User::orderBy($orderBy, $order)->latest();
        
        }
        public function getUser(string $encryptedId): User | Collection
        {
            return User::findOrFail(decrypt($encryptedId));
        }
        public function getDeletedUser(string $encryptedId): User | Collection 
        {
            return User::onlyTrashed()->findOrFail(decrypt($encryptedId));
        }

        public function createUser(array $data, $file = null): User 
        {
            return DB::transaction(function () use ($data, $file){
                $data['created_by'] = user()->id;
                $user = User::create($data);
                $user->assignRole($user->role->name);
                return $user;
            });
        }

        public function delete(User $user): void 
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


      