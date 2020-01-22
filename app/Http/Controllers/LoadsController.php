<?php

namespace App\Http\Controllers;
use App\Loads;
use App\OrdersLoad;
use Illuminate\Http\Request;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class LoadsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Loads = Loads::all();
         
        return response()->json(new JsonResponse(['items' => $Loads]));
       
       
         //return json_encode($loads);
            
        //return view('home');
    }

      public function assign_order(request $request)
    {
         
        $input = $request->all();
        OrdersLoad::create($input);
        
        return "success";
        // print_r($request->file('file'));
        // exit;
        //  $post = new Post;
        //  $post->title = $request->title; 
        //  $post->body = $request->title;
        //  $post->save();
        //  return redirect('/post');
        //
    }


    
}
