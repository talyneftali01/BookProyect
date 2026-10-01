<?php
namespace App\Http\Controllers\Api;

use App\Services\Api\BookService;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;

class ReportController extends BaseController{
    public function __construct(protected BookService $BookService)    {}

    public function listReport(Request $request){
        if($request -> user() -> role_id !== '01' && $request -> user() -> role_id !== '02'){
            return $this -> error([],'Acceso denegado, permisos insuficiente',403);
        }
        $types = $this -> BookService -> listReport();
        return $this -> success($types, 'Lista de tipos de reportes');
    }
    public function listReportBooks(Request $request){
        if($request -> user() -> role_id !== '01' && $request -> user() -> role_id !== '02'){
            return $this -> error([],'Acceso denegado, permisos insuficiente',403);
        }
        $reports = $this -> BookService -> listReportBooks();
        return $this -> success($reports, 'Libros reportados');
    }
}