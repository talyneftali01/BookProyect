<?php
namespace App\Http\Controllers\Api;

use App\Services\Api\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController{
    public function __construct(protected UserService $userService){}

    public function profile(Request $request){
        $user = $request->user();
        $librosSubidos = $user->books()->with('gener')->get();
          $favoritosGuardados = $user->favorites()->with('book.gener')->get()->map(function($favorite) {
            return $favorite->book; // Retornamos directamente el objeto del libro para limpiar el JSON
        });
        //obtener usuario de la sesion
        $result = [
            
            'Libros_subidos'=>$librosSubidos,
            'Guardados'=>$favoritosGuardados
        ];
        return $this -> success($result,'Datos de perfil Cagados exitosamente');
    }
    public function getRoles(Request $request){
        if($request -> user()->role_id !=='01'){
            return $this ->error([],"Acceso denegado, permisos insuficientes",403);
        }
        $roles= $this -> userService -> listRole();
        return $this -> success($roles,'Lista de roles');
    }
    public function getAllUsers(Request $request){
        if($request -> user()->role_id !=='01'){
            return $this ->error([],"Acceso denegado, permisos insuficientes",403);
        }
        $allUsers = $this -> userService -> listAll();
        return $this -> success($allUsers, 'Lista de usuarios existente');
    }
    public function getActiveUsers(Request $request){
        if($request -> user()->role_id !=='01'){
            return $this ->error([],"Acceso denegado, permisos insuficientes",403);
        }
        $active= $this -> userService -> listActive();
        return $this -> success($active,'Lista de usuarios activos');

    }
    public function show(Request $request, int $id){
        if($request -> user() -> role_id !== '01'){
            return $this -> error([],'Acceso denegado, permisos insuficiente');
        }
        $user = $this -> userService -> findById($id);
        if(!$user){
            return $this -> error([],'Usuario no encontrado o no existe',404);
        }
        return $this -> success($user,'Usuario encontrado y obtenido exitosamente');
    }
    public function update(Request $request, int $id){
        if ($request->user()->role_id !== '01') {
            if ($request->user()->id !== $id) {
                return $this->error([], "Acceso denegado, permisos insuficientes", 403);
            }
        }
        if($request -> user() -> role_id !== '01'){
            $validated = $request -> validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,'.$id,
                'password' => 'sometimes|string|min:8',
            ]);
        }
        else{
            $validated = $request -> validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,'.$id,
                'password' => 'sometimes|string|min:8',
                'role_id' => 'sometimes|string|size:2|exists:roles,id'
            ]);
        }
        $user= $this -> userService -> updateUser($id,$validated);
        if(!$user){
            return $this -> error([],'Usuario no encontrado o obtenido',404);
        }
        return $this -> success($user, 'Usuario modificado');

    }
    public function delete(Request $request, int $id){
        if($request -> user() -> role_id !== '01'){
            if($request -> user() -> id !== $id){
                return $this -> error([],'acceso denegado, permiso insuficicente',403);
            }
        }
        $result = $this -> userService -> deleteUser($id);
        if ($result){
            return $this -> success($result, 'eliminado correctamente');
        }
        return $this -> error([], 'usuario no encontrado o ya eliminado',404);
    }
    
}