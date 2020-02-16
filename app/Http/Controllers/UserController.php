<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use App\DriverInfo;
/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $userQuery = User::query();
        
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        
        $role = Arr::get($searchParams, 'role', '');
        
        $keyword = Arr::get($searchParams, 'keyword', '');
        
        if (!empty($role)) {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }

        if (!empty($keyword)) {
            $userQuery->where('name', 'LIKE', '%' . $keyword . '%');
            $userQuery->where('email', 'LIKE', '%' . $keyword . '%');
        }

        return UserResource::collection($userQuery->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'password' => ['required', 'min:6'],
                    'confirmPassword' => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } 
        else {
            $params = $request->all();
            $user = User::create([
                'name' => $params['name'],
                'email' => $params['email'],
                'password' => Hash::make($params['password']),
            ]);

            $role = Role::findByName($params['role']);
            
            $user->syncRoles($role);
            
            return new UserResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $email = $request->get('email');
            $found = User::where('email', $email)->first();
            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'Email has been taken'], 403);
            }

            $user->name = $request->get('name');
            $user->email = $email;
            $user->save();
            return new UserResource($user);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function($permission) {
                return $permission['id'];
            },

            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not delete admin user'], 403);
        }

        try {
            $user->delete();
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required',
            'phone_no' => 'required',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                'required',
                'array'
            ],
        ];
    }

    private function getValidationR($isNew = true)
    {
        return [
            'truct_number' => 'sometimes|required',
            'company_name' => 'sometimes|required',
            'role_id' => 'required',    
            'password' => ['required', 'min:6'],
            'confirmPassword' => 'required_with:password|same:password|min:6',
        ];
    }



    /**
     * Mobile Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signUp(Request $request)
    {

        if(isset($request['name']) and $request['name']!=''){
            $request['name'] = $request['name'];
        }else{
            $request['name'] = "islam gulshan";
        }   
        $request['email'] = "islam".@$request['phone_no']."@gmail.com";
        
        $request['roles'] = ['2'];        
        
        $validator = Validator::make(
            $request->all(),    
            array_merge(
                $this->getValidationRules(),
                [
                    'password' => ['required', 'min:6'],
                    'confirmPassword' => 'same:password',
                ]
            )
        );

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        else {
            
            $params = $request->all();
            
            $user = User::create([
                
                'name' =>$request['name'],
                'email' =>'islam'.$params['phone_no'].'@gmail.com',
                'company_name' => @$params['company_name'],
                'role_id'     => $params['role_id'], 
                'phone_no'     => $params['phone_no'], 
                'truct_number' => @$params['truct_number'],
                'password' => Hash::make($params['password']),
                
            ]);

            $role = Role::findByName($params['role']);
            
            $user->syncRoles($role);

            $user = array(
                    'phone'=>$params['phone_no'],
                    'id'=>$user->id,
                    'loginstatus'=>0);
            return response()->json(['success' => 'User sign up successfully !', 'data'=>$user], 200);


           // return new UserResource($user);
        }
       
       
        

            // $params = $request->all();
            // $phone_no= date("Y-m-d H:i:s", strtotime("now"));
            // $user = User::create([
            //     'name' =>'islam',
            //     'email' =>'islam'.$phone_no.'@gmail.com',
            //     'company_name' => @$params['company_name'],
            //     'phone_no'     => $phone_no, 
            //     'truct_number' => @$params['truct_number'],
            //     'password' => Hash::make($params['password']),
            // ]);

         
            
            // $user->syncRoles($role);
           
           

           
    }


    /*
    /  Forgot Password 
    */

    public function reset_password(request $request)
    {
        
        


        $matchThese = array('phone_no' =>$request->get('phone_no'));

        $found = User::where($matchThese)->first();
         
       if ($found) {  
            if($found->status==1){
                return response()->json(['success' => 'Number exists !','data'=>$found], 200);
            }else{
                return response()->json(['success' => 'Not active!'], 200);

            }
            
            // user doesn't exist
        }else{

            return response()->json(['error' => 'Number not exists'], 200);
             
           
        }
    
    }


    /*
    /  Profile Password 
    */

    public function profile(request $request)
    {
        
        

       $user= $request->get('user_id');

        $matchThese = array('id' =>$request->get('user_id'));

        $found = User::where($matchThese)->first();
         
       if ($found) {  
             
                $subjectResults = DB::select(DB::raw("select users.*,driver_infos.* from  users  
    Left JOIN driver_infos   ON driver_infos.user_id = users.id
    where  users.id =$user"));
                   
                return response()->json(['success' => 'Not active!','data'=>$subjectResults], 200);
 
            // user doesn't exist
        }else{

            return response()->json(['error' => 'user Not Exist '], 200);
             
           
        }
    
    }


    /*
    /  Profile Update 
    */

    public function profile_update(request $request)
    {
        
        

       $user= $request->get('user_id');
       $matchThese = array('id' =>$request->get('user_id'));

       $found = User::where($matchThese)->first();
         
       if ($found) {  
            
            User::where('id', $request->get('user_id'))
            ->update([
                   'name' => $request->get('name')
            ]);

            $matchThese = array('user_id' =>$request->get('user_id'));

            $found = DriverInfo::where($matchThese)->first();


            $input = $request->all();
            
            if($file = $request->file('photo')){
                $name = time().$file->getClientOriginalName();
                $file->move('images',$name);
                $input['photo'] = $name; 
            }
            if($file = $request->file('Truck_registration')){
                $name = time().$file->getClientOriginalName();
                $file->move('images',$name);
                $input['Truck_registration'] = $name; 
            }
            if($file = $request->file('licence')){
                $name = time().$file->getClientOriginalName();
                $file->move('images',$name);
                $input['licence'] = $name; 
            }

            if($file = $request->file('NIC_back')){
                $name = time().$file->getClientOriginalName();
                $file->move('images',$name);
                $input['NIC_back'] = $name; 
            }

            if($file = $request->file('NIC_front')){
                $name = time().$file->getClientOriginalName();
                $file->move('images',$name);
                $input['NIC_front'] = $name; 
            }

             if($file = $request->file('truck_photo_no_plate')){
                $name = time().$file->getClientOriginalName();
                $file->move('images',$name);
                $input['truck_photo_no_plate'] = $name; 
            }

             
             

            if($found){

                $found->update($input); 
                 
            } else{
                DriverInfo::create($input);
                
            }

              return response()->json(['success' => 'User Profile update successfully !'], 200);

    //             $subjectResults = DB::select(DB::raw("select users.*,driver_info.* from  users  
    // Left JOIN driver_info   ON driver_info.user_id = users.id
    // where  users.id =$user"));
                   
    //             return response()->json(['success' => 'Not active!','data'=>$subjectResults], 200);
 
            // user doesn't exist
        }else{

            return response()->json(['error' => 'user Not Exist '], 200);
             
           
        }
    
    }



    /*
    /  Update Password 
    */

    public function update_password(request $request)
    {
        $user_id= $request->get('user_id');
        $password = Hash::make($request->get('cpassword'));

        if($request->get('password') == $request->get('cpassword')){
             
             User::where('id', $user_id)
       ->update([
           'password' => $password
        ]);
       return response()->json(['success' => 'Password updated successfully!'], 200);
           
             


        }else{
             return response()->json(['success' => 'Password and conform password are not same!'], 200);
           
        }

    
    }


    /*
    /  Online Status User 
    */

    public function online(request $request)
    {
        $user_id= $request->get('user_id');
        $longitude= $request->get('longitude');
        $latitude= $request->get('latitude');
        
        $matchThese = array('id' =>$request->get('user_id'));
        $found = User::where($matchThese)->first();
         
       if ($found) {

             User::where('id', $user_id)
       ->update([
           'latitude' => $latitude,
           'longitude' => $longitude,
           'online' => 1
        ]);
             return response()->json(['success' => 'User status online!'], 200);

       }else{
             return response()->json(['success' => 'User Not exists!'], 200);
       }   
    }

    /*
    /  Offline  Status User 
    */

    public function offline(request $request)
    {
        $user_id= $request->get('user_id');
        $longitude= $request->get('longitude');
        $latitude= $request->get('latitude');
        
        $matchThese = array('id' =>$request->get('user_id'));
        $found = User::where($matchThese)->first();
         
       if ($found) {

             User::where('id', $user_id)
       ->update([
           'latitude' => $latitude,
           'longitude' => $longitude,
           'online' => 0
        ]);
             return response()->json(['success' => 'User status Offline!'], 200);

       }else{
             return response()->json(['success' => 'User Not exists!'], 200);
       }   
    }

    /*
    /  Update  Location User 
    */

    public function update_location(request $request)
    {
        $user_id= $request->get('user_id');
        $longitude= $request->get('longitude');
        $latitude= $request->get('latitude');
        
        $matchThese = array('id' =>$request->get('user_id'));
        $found = User::where($matchThese)->first();
         
       if ($found) {

             User::where('id', $user_id)
       ->update([
           'latitude' => $latitude,
           'longitude' => $longitude
        ]);
             return response()->json(['success' => 'User location update !'], 200);

       }else{
             return response()->json(['success' => 'User Not exists!'], 200);
       }   
    }



}
