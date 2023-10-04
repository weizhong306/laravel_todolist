<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/hello/{name?}', function ($name="World"){
    return view("hello-name", [
        "name" => $name,
    ]);
});

Route::get('/number/{number}', function ($number){
    return $number;
})->where(["number"=>'[0-9]+']); // 設定條件：輸入的內容要由0-9組成

Route::any('/hello-world/', function (){
    return '<h1>Hello, World</h1>';
})->name("hello-world"); // 將路由命名為hello-world

// 將url分群好做分類管理，以下兩種寫法：
// Route::prefix('admin')->group(function (){
//     Route::get('users', function (){
//         // Matches The "/admin/users" URL
//     });
// });

Route::group(['prefix'=>'admin'], function(){
    Route::get('users', function(){
        // Match The "admin/users" URL
    });
    // Route::get('/{activity_id}', '$callback');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('todos', TodoController::class);

Route::group(['prefix' => 'todo',
              'as' => 'todo/',
              'namespace' => 'Todo',],

              function(){
                Route::get('/', [TodoController::class, 'index']);
                Route::post('create', [TodoController::class, 'create']);
                Route::delete('delete/{id}', [TodoController::class, 'delete']);
                Route::put('update/{id}', [TodoController::class, 'update']);
                Route::post('search/{name}', [TodoController::class, 'search']);
                Route::post('details/{id}', [TodoController::class, 'details']); 
              });
