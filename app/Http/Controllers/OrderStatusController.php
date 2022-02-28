<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\OrderStatus;

class OrderStatusController extends Controller {
    function index(Request $request) {
        try {
            $statuses = OrderStatus::whereRaw('1=1')
                ->orderBy(
                    empty($request->query('sortBy')) ? 'id' : $request->query('sortBy'),
                    !empty($request->query('desc')) ? 'desc' : 'asc'
                );
            
            if (!empty($request->query('page'))) {
                $statuses->skip($request->query('page'));
            }
            
            if (!empty($request->query('limit'))) {
                $statuses->take($request->query('limit'));
            }
            return response()->json($statuses->get(), 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(null, 500);
        }
    }

    function create() {}
    
    function show() {}
    
    function update() {}
    
    function delete() {}
}
