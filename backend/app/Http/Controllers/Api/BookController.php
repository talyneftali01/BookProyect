<?php
namespace App\Http\Controllers\Api;

use App\Services\Api\BookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends BaseController
{
    public function __construct(protected BookService $bookService) {}

    public function listActive(){
        $books = $this->bookService->listActive();
        return $this->success($books, 'Libros obtenidos exitosamente');
    }
    public function listGeners(){
        $geners = $this -> bookService -> listGeners();
        return $this -> success($geners, 'lista de generos ');
    }
    public function listAll(Request $request)
    {
        if ($request->user()->role_id !== '01') {
            return $this->error([], 'Acceso denegado. Permisos insuficientes.', 403);
        }

        $books = $this->bookService->listAllBook();
        return $this->success($books, 'Historial completo de libros para Activity obtenido');
    }
    public function addBook(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'required|string|max:255',
            'gener_id' => 'required|integer|exists:geners,id', // Valida contra tu tabla 'geners'
        ]);

        $book = $this->bookService->addBook($validated, $request->user()->id);
        return $this->success($book, 'Libro publicado exitosamente', 201);
    }
    public function updateBook(Request $request, int $id)
    {
        $book = DB::table('books')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$book) {
            return $this->error([], 'Libro no encontrado', 404);
        }
        if ($request->user()->role_id !== '01' && $book->user_id !== $request->user()->id) {
            return $this->error([], 'No tienes permisos para modificar este libro', 403);
        }

        $validated = $request->validate([
            'title'  => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'gener_id' => 'required|integer|exists:geners,id',
        ]);

        $updated = $this->bookService->updateBook($id, $validated);
        return $this->success($updated, 'Libro actualizado correctamente');
    }
    public function deleteBook(Request $request, int $id)
    {
        $book = DB::table('books')->where('id', $id)->whereNull('deleted_at')->first();
        if (!$book) {
            return $this->error([], 'Libro no encontrado', 404);
        }
        if ($request->user()->role_id !== '01' && $request->user()->role_id !== '02' && $book->user_id !== $request->user()->id) {
            return $this->error([], 'No tienes permisos para eliminar este libro', 403);
        }

        $this->bookService->deleteBook($id);
        return $this->success(null, 'Libro eliminado lógicamente');
    }

    public function calificar(Request $request, int $id)
    {
        $bookExists= DB::table('books')->where('id', $id)->whereNull('deleted_at')->exists();
        if (!$bookExists) {
            return $this->error([], 'Libro no encontrado', 404);
        }
        $validated = $request->validate([
            'stars' => 'required|integer|between:1,5'
        ]);

        $this->bookService->calificar($id, $request->user()->id, $validated['stars']);
        return $this->success(null, 'Calificación registrada de forma exitosa');
    }
    
    public function favorite(Request $request, int $id)
    {
        $bookExists= DB::table('books')->where('id', $id)->whereNull('deleted_at')->exists();
        if (!$bookExists) {
            return $this->error([], 'Libro no encontrado', 404);
        }
        $res = $this->bookService->favorite($id, $request->user()->id);
        return $this->success(['en_favoritos' => $res['en_favoritos']], $res['message']);
    }

    public function report(Request $request, int $id)
    {
        $bookExists= DB::table('books')->where('id', $id)->whereNull('deleted_at')->exists();
        if (!$bookExists) {
            return $this->error([], 'Libro no encontrado', 404);
        }
        $validated = $request->validate([
            'type_report' => 'required|integer|exists:report_type,id' 
        ]);

        $success = $this->bookService->report($id, $request->user()->id, $validated['type_report']);
        
        if(!$success){
            return $this -> error([],'no se pudo registrar el repote',500);
        }
        return $this -> success($success, 'reporte guardado corecctamente');

    }
}
