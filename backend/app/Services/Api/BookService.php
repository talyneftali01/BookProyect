<?php 
namespace App\Services\Api;

use App\Models\Book;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function listGeners(){
        return DB::table('geners')->get(['id', 'name']);
    }
    public function listReport(){
        return DB::table('report_type')->get(['id','type']);
    }
    public function listActive()// listar todos libros activos con promedio de calificaciones
    {
        return Book::with(['gener','user']) 
            ->get() 
            ->map(function($book){
                $book->qualification_average= DB::table('qualifications')
                ->where('book_id',$book->id)
                ->avg('stars')??0;
            return $book;

        });
    }
    //libros existentes
    public function listAllBook(){
        return Book::withTrashed() ->get(['id','title','created_at','updated_at','deleted_at']);
    }
    public function addBook(array $data, int $userId):?Book{
        return Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'gener_id'=> $data['gener_id'],
            'user_id' => $userId,
        ]);
    }
    public function updateBook(int $id, array $data):?book{
        $book = Book::find($id);
        if($book){
            $book->update($data);
            return $book;
        }
        return null;
    }
    public function deleteBook(int $id):bool{
        $book = Book::find($id);
        if($book){
            DB::table('favorites')->where('book_id',$id)->delete();
            DB::table('qualifications')->where('book_id',$id)->delete();
            return $book->delete();
        }
        return false;
    }
    //
    public function calificar(int $bookId, int $userId, int $stars){
        return DB::table('qualifications')->updateOrInsert(
            ['book_id' => $bookId, 'user_id' => $userId],
            ['stars' => $stars, 'updated_at' => now(), 'created_at' => now()]
        );
    }

    public function favorite(int $bookId, int $userId): array{
        $exists = DB::table('favorites')
            ->where('book_id', $bookId)
            ->where('user_id', $userId)
            ->first();

        if ($exists) {
            DB::table('favorites')->where('id', $exists->id)->delete();
            return ['en_favoritos' => false, 'message' => 'Removido de favoritos'];
        }

        DB::table('favorites')->insert([
            'book_id'   => $bookId,
            'user_id'    => $userId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return ['en_favoritos' => true, 'message' => 'Agregado a favoritos'];
    }

    public function report(int $bookId, int $userId, int $tipoReporteId): bool{
        // Insertamos el nuevo reporte
        return DB::table('reports')->insert([
            'type_report' => $tipoReporteId,
            'user_id'         => $userId,
            'book_id'        => $bookId,
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
    }
    public function listReportBooks(){
       $query =DB::table('books ') 
       -> join('geners','books.gener_id','=','geners.id')
       -> select(
            'books.id as book_id',
            'books.title',
            'books.author',
            'geners.name as gener_name',
       )
       ->whereNull('books.deleted_at');
       return $query;
    }
}