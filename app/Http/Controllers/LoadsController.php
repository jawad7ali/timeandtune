<?php

namespace App\Http\Controllers;
use App\Loads;
use App\OrdersLoad;
use Illuminate\Http\Request;

use DB;
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
        
        
        $matchThese = array('load_id' =>$request->get('load_id'), 'user_id' =>$request->get('user_id'));
         
        $found = OrdersLoad::where($matchThese)->first();
        dump($found);
       if ($found) {  
            return response()->json(['error' => 'Already confirm'], 200);
            // user doesn't exist
        }else{

            $input = $request->all();
            
            if(isset($input['mybidoffer'])){
              $input['mybidoffer']=$input['mybidoffer'];
            }else{
              $input['mybidoffer']=$input['shipper_offer'];
            }

            OrdersLoad::create($input);
            return response()->json(['success' => 'confrim successfully !'], 200);
             
           
        }
        // print_r($request->file('file'));
        // exit;
        //  $post = new Post;
        //  $post->title = $request->title; 
        //  $post->body = $request->title;
        //  $post->save();
        //  return redirect('/post');
        //
    }


    /*  Pending Order */
     public function pending_order(request $request)
    {
         
        
      $matchThese = array('user_id' =>$request->get('user_id'), 'status' => 'pending');         
      $userid=  $request->get('user_id');
      $found = OrdersLoad::where($matchThese)->get();
      $founds= count($found);
       if ($founds==0) {  
            
            return response()->json(['error' => 'No pending order'], 200);
            // user doesn't exist
        }else{
            
            $value = '';
            $i = 1;
            foreach ($found as $foundss) {  

                $value .= $foundss->load_id ;
                if($founds!=$i){
                   $value .= ',';
                }
                $i++;
            }
         
         $subjectResults = DB::select(DB::raw("select orders_loads.id as BID,orders_loads.user_id as UserID,orders_loads.*,loads.*
    from loads  
    JOIN orders_loads   ON orders_loads.load_id = loads.id
    where  loads.id in ($value) and  orders_loads.user_id=$userid"));
        
      
           
        return response()->json(['success' => 'User sign up successfully !', 'data'=>$subjectResults], 200);


             
           
        }
    }


    /*  Order History Order */
     public function history_order(request $request)
    {
         
        
        $matchThese = array('user_id' =>$request->get('user_id'), 'status' => 'completed');         
       $userid=  $request->get('user_id');
      $found = OrdersLoad::where($matchThese)->get();

        $founds= count($found);

         

        
       if ($founds==0) {  
            
            return response()->json(['error' => 'No History '], 200);
            // user doesn't exist
        }else{
            
            $value = '';
            $i = 1;
            foreach ($found as $foundss) {  

                $value .= $foundss->load_id ;
                if($founds!=$i){
                   $value .= ',';
                }
                $i++;
            }
         
         $subjectResults = DB::select(DB::raw("select orders_loads.id as BID,orders_loads.*,loads.*
    from loads  
    JOIN orders_loads   ON orders_loads.load_id = loads.id
    where  loads.id in ($value) and  orders_loads.user_id=$userid"));
        
      
           
        return response()->json(['success' => 'Order history', 'data'=>$subjectResults], 200);


             
           
        }
    }



    /* Edit order */    
    public function edit_order(request $request)
        {
           
           $matchThese = array('id' =>$request->get('id'), 'user_id' =>$request->get('user_id'));

         

        $found = OrdersLoad::where($matchThese)->first();
         
       if ($found) {  

              OrdersLoad::where('id', $request->get('id'))
       ->update([
           'mybidoffer' => $request->get('mybidoffer')
        ]);

            return response()->json(['success' => 'Updated successfully'], 200);
            // user doesn't exist
        }else{

            
            return response()->json(['error' => 'Already confirm'], 200);
 
           
        }
        
            # code...
        }    
        // print_r($request->file('file'));
        // exit;
        //  $post = new Post;
        //  $post->title = $request->title; 
        //  $post->body = $request->title;
        //  $post->save();
        //  return redirect('/post');
        //

    /* Edit order */    
    public function cancel_order(request $request)
        {
           $matchThese = array('id' =>$request->get('id'), 'user_id' =>$request->get('user_id'));
            $found = OrdersLoad::where($matchThese)->first();
       if ($found) {  
            OrdersLoad::where('id', $request->get('id'), 'user_id' =>$request->get('user_id'))
            ->update([
                   'status' => 'cancel'
            ]);
            return response()->json(['success' => 'Cancel successfully'], 200);
           
        }else{ 
            return response()->json(['error' => 'No order'], 200);
        }
        
        
    }  


    /* My active order */    
    public function active_order(request $request)
        {

          $matchThese = array('user_id' =>$request->get('user_id'),'status' =>'active');
          $found = OrdersLoad::where($matchThese)->get();
          $founds= count($found);
         if ($found) { 
            $value =  array() ;
            foreach ($found as $foundss) {  
                $value[] = $foundss->load_id;
            }
         
            $models = Loads::whereIn('id', $value)->get(); 
            return response()->json(['success' => 'My order !', 'data'=>$models], 200);

          }else{

              return response()->json(['error' => 'No order'], 200); 
             
          }
          
        
    } 

    /* accept order */    
    public function accept_order(request $request)
        {

          $matchThese = array('user_id' =>$request->get('user_id'),'id' =>$request->get('id'));
          $found = OrdersLoad::where($matchThese)->get();
          $founds= count($found);
         if ($found) { 
            OrdersLoad::where('id', $request->get('id'))
            ->update([
                   'status' => 'in progress'
            ]);

            return response()->json(['success' => 'Accept Order!'], 200);

          }else{

              return response()->json(['error' => 'No order'], 200); 
             
          }
          
        
    }


    /* reject order */    
    public function reject_order(request $request)
        {

          $matchThese = array('user_id' =>$request->get('user_id'),'id' =>$request->get('id'));
          $found = OrdersLoad::where($matchThese)->get();
          $founds= count($found);
          if ($found) { 
            // OrdersLoad::where('id', $request->get('id'))
            // ->update([
            //        'status' => 'rejected'
            // ]);
            OrdersLoad::where('id',$request->get('id'))->delete();
            return response()->json(['success' => 'Delete Order !'], 200);
          }else{
              return response()->json(['error' => 'No order'], 200);       
          }     
        
    }

    /* Tranzit order */    
    public function transit_order(request $request)
        {

          $matchThese = array('user_id' =>$request->get('user_id'),'status' =>'in process');
          $userid = $request->get('user_id');
          $found = OrdersLoad::where($matchThese)->get();
          $founds= count($found);
         if ($founds) { 
              $value = '';
            $i = 1;
            foreach ($found as $foundss) {  

                $value .= $foundss->load_id ;
                if($founds!=$i){
                   $value .= ',';
                }
                $i++;
            }
                 
            $subjectResults = DB::select(DB::raw("select orders_loads.id as BID,orders_loads.*,loads.*
    from loads  
    JOIN orders_loads   ON orders_loads.load_id = loads.id
    where  loads.id in ($value) and  orders_loads.user_id=$userid"));
 
            return response()->json(['success' => 'My order !', 'data'=>$subjectResults], 200);

          }else{

              return response()->json(['error' => 'No order'], 200); 
             
          }
          
        
    }


    /* Start Journey order */    
    public function start_order(request $request)
        {

          $matchThese = array('user_id' =>$request->get('user_id'),'id' =>$request->get('bid'));
          $found = OrdersLoad::where($matchThese)->get();
          $founds= count($found);
          if ($found) { 
            // OrdersLoad::where('id', $request->get('id'))
            // ->update([
            //        'status' => 'rejected'
            // ]);

            OrdersLoad::where('id', $request->get('bid'))
            ->update([
                   'status' => 'start'
            ]);

            return response()->json(['success' => 'Order started !'], 200);

          }else{
              return response()->json(['error' => 'No order'], 200);       
          }     
        
    }



    /* Stop Journey order */    
    public function complete_order(request $request)
        {

          $matchThese = array('user_id' =>$request->get('user_id'),'id' =>$request->get('bid'));
          $found = OrdersLoad::where($matchThese)->get();
          $founds= count($found);
          if ($found) { 
            
            OrdersLoad::where('id', $request->get('bid'))
            ->update([
                   'status' => 'completed'
            ]);

            return response()->json(['success' => 'Order completed !'], 200);

          }else{
              return response()->json(['error' => 'No order'], 200);       
          }     
        
    }



    
}
