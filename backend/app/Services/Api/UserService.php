<?php 
namespace App\Services\Api;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService{
    //lista de usuarios activos
    public function listActive(){
        return User :: with('role')->get(); 
    }
    //lista de usaurios existentes
    public function listAll(){
        return User::withTrashed()->get(['id','name','created_at','updated_at','deleted_at']);
    }

    public function findById (int $id):?User{
        return User::with('role')->find($id);
    }

    public function updateUser(int $id, array $data):?User{
        $user= User::find($id);
        if(!$user){
            return null;
        }
        if(isset($data['password'])){
            $data['password']=Hash::make($data['password']);
        }
        $user->update($data);
        return $user;
    }

    public function deleteUser(int $id):bool{
        $user = User::find($id);
        if($user){
            $user->tokens()->delete();
            return $user->delete();
        }
        return false;
    }
    //lista de roles de usuarios
    public function listRole(){
        return DB::table('roles')->get();
    }

}
