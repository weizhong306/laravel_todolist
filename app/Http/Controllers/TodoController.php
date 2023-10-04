<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Todo;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        // 呼叫Todo Model並使用create方法，將使用者的請求資料用all()方法轉為陣列
        $todo = Todo::create($request->all());
        // 用refresh再次去資料庫查一次資料，因前者新存進資料庫的資料一開始不會被抓到
        $todo = $todo->refresh();
        // 將$todo寫入資料庫後產生的實體物件資料，包含在HTTP協定內容中回傳給客戶端
        return response($todo, Response::HTTP_CREATED); 
    }

    // Index
    public function index(Request $requests)  # $requests 使用者請求時傳入的資料
    {
        $offset = $requests->query("offset", 0); // 從第幾比資料開始，預設從第0筆開始
        $limit = $requests->query("limit", 2);  // 限制每次取幾筆資料，預設2筆
        $page = $requests->query("page");   // 標示現在是第幾頁

        if($page){
            $offset = ($page - 1) * $limit;
        }else{
            $page = ($offset / $limit) + 1;
        }

        $requests->merge([
            "offset" => $offset,
            "page" => $page,
        ]);

        // 透過Todo Model 去抓資料，並用id進行排序，設置要從第比己開始抓，限制抓幾筆
        $todos = Todo::orderBy('id')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
        // dd($todos);
        return response()->json(["todos"=>$todos, "page"=>$page,]);
        // return response()->json('Hello world');
    }
    
    // Create
    public function create(Request $request){
        $todo = Todo::create($request->all());
        $todo = $todo->refresh();
        return response($todo, Response::HTTP_CREATED);
    }

    // Delete
    public function delete(Request $request, $id){
        // dd($request);
        $todo = Todo::find($id);

        if(! $todo){
            abort(403);
        }
        $todo ->delete();
        Log::info("Delete Blog Post, the id is $id");
        return response(null, Response::HTTP_NO_CONTENT);
    }

    // Update
    public function update(Request $request, $id){
        // dd($id);
        // dd($request->all()['updated_at']);
        $todo = Todo::find($id);

        if(! $todo){
            abort(403);
        }

        $name = $request->input("name", "未命名代辦事項");
        $description = $request->input("description", "沒有敘述");

        $todo->name = $name;
        $todo->description = $description;

        $todo->update();

        Log::info("Update Blog Post, the id is $id");

        return response()->json($id);
    }
    // Search todo list name
    public function search(Request $request, $name){
        // dd($name);
        $todos = Todo::select("*")
                        ->where('name', "LIKE", "%{$name}%")
                        ->get();
        // dd($todos);
        return response()->json(["todos"=>$todos]);
    }
    // Read todo description
    public function details(Request $request, $id){
        $description = Todo::find($id)['description'];
        // dd($description);

        return $description;
    }
}
