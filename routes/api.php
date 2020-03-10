<?php

use Illuminate\Http\Request;
use \App\Laravue\Faker;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

 




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
    Route::post('auth/login', 'AuthController@login');
    Route::group(['middleware' => 'api'], function () {
    
    Route::post('mobile/load', 'LoadsController@index');

    Route::post('assign/order', 'LoadsController@assign_order');

    Route::post('user/forgot', 'UserController@reset_password');

    Route::post('user/forgot/update', 'UserController@update_password');

    Route::post('user/profile', 'UserController@profile');

    Route::post('user/profile/update', 'UserController@profile_update');    

    Route::post('user/online', 'UserController@online');

    Route::post('user/offline', 'UserController@offline');

    Route::post('user/location/update', 'UserController@update_location');

    Route::post('order/pending', 'LoadsController@pending_order');

    Route::post('order/edit', 'LoadsController@edit_order');

    Route::post('order/cancel', 'LoadsController@cancel_order');

    Route::post('order/active', 'LoadsController@active_order');

    Route::post('order/accept', 'LoadsController@accept_order');

    Route::post('order/reject', 'LoadsController@reject_order');

    Route::post('order/transit', 'LoadsController@transit_order');

    Route::post('order/start', 'LoadsController@start_order');

    Route::post('order/complete', 'LoadsController@complete_order');

	Route::post('order/history', 'LoadsController@history_order');     
   
    //mobile API'S
    
    Route::post('auth/mobile/login', 'AuthController@mobile_login');
    
    //mobile Sign Up API'S
    Route::post('signup', 'UserController@signUp');   
    
    
    Route::get('auth/user', 'AuthController@user');
    
    Route::group(['middleware' => 'auth:api'], function () {
        
        Route::post('auth/logout', 'AuthController@logout');
    });


    Route::apiResource('users', 'UserController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    
    Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    
    Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    
    Route::apiResource('roles', 'RoleController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    
    Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    
    Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);

    // Fake APIs


    Route::get('/orders', function () {
        $rowsNumber = 8;
        $data = [];
        for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
            $row = [
                'order_no' => 'LARAVUE' . mt_rand(1000000, 9999999),
                'price' => mt_rand(10000, 999999),
                'status' => Faker::randomInArray(['success', 'pending']),
            ];

            $data[] = $row;
        }

        return response()->json(new JsonResponse(['items' => $data]));
    });


   
    Route::get('/articles', function () {
        $rowsNumber = 10;
       
        for ($rowIndex = 0; $rowIndex < $rowsNumber; $rowIndex++) {
            $row = [
                'id' => mt_rand(100, 10000),
                'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
                'title' => Faker::randomString(mt_rand(20, 50)),
                'author' => Faker::randomString(mt_rand(5, 10)),
                'comment_disabled' => Faker::randomBoolean(),
                'content' => Faker::randomString(mt_rand(100, 300)),
                'content_short' => Faker::randomString(mt_rand(30, 50)),
                'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
                'forecast' => mt_rand(100, 9999) / 100,
                'image_uri' => 'https://via.placeholder.com/400x300',
                'importance' => mt_rand(1, 3),
                'pageviews' => mt_rand(10000, 999999),
                'reviewer' => Faker::randomString(mt_rand(5, 10)),
                'timestamp' => Faker::randomDateTime()->getTimestamp(),
                'type' => Faker::randomInArray(['US', 'VI', 'JA']),

            ];

            $data[] = $row;
        }

        return response()->json(new JsonResponse(['items' => $data, 'total' => mt_rand(1000, 10000)]));
    });


        /* Carier */

        Route::get('/carriers', function () {
            $userQuery = User::all();
            // $no_of_row = count($user_list);
            // //echo $no_of_row;
          // $typeofrole= $val->role_types->name;
          $data =[];
            foreach( $userQuery as $val)
            {
                // if ( isset($val->role_id) and $val->role_id!=''){
                //     $role_type ='fds';
                //     //@$role_type = Role_type::findOrFail(@$val->role_id)->name;
                // } else{
                //     $role_type = 'dsadsad';
                // }

                // if ( isset($val->phone_no) and $val->phone_no!=''){
                //     $phone_no ='fds';
                //     //@$role_type = Role_type::findOrFail(@$val->role_id)->name;
                // } else{
                //     $phone_no = 'dsadsad';
                // }

                  
                $row = [
                 'id' => mt_rand(100, 10000),
                'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
                'title' => Faker::randomString(mt_rand(20, 50)),
                'author' => Faker::randomString(mt_rand(5, 10)),
                'comment_disabled' => Faker::randomBoolean(),
                'content' => Faker::randomString(mt_rand(100, 300)),
                'content_short' => Faker::randomString(mt_rand(30, 50)),
                'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
                'forecast' => mt_rand(100, 9999) / 100,
                'image_uri' => 'https://via.placeholder.com/400x300',
                'importance' => mt_rand(1, 3),
                'pageviews' => mt_rand(10000, 999999),
                'reviewer' => Faker::randomString(mt_rand(5, 10)),
                'timestamp' => Faker::randomDateTime()->getTimestamp(),
                'type' => Faker::randomInArray(['US', 'VI', 'JA'])
                ] ; 

                

            }
                
            //  return response()->json(new JsonResponse(['items'=>$data]));

    
            return response()->json(new JsonResponse(['items' => $data, 'total' => mt_rand(1000, 10000)]));
    });

    Route::get('articles/{id}', function ($id) {
        $article = [
            'id' => $id,
            'display_time' => Faker::randomDateTime()->format('Y-m-d H:i:s'),
            'title' => Faker::randomString(mt_rand(20, 50)),
            'author' => Faker::randomString(mt_rand(5, 10)),
            'comment_disabled' => Faker::randomBoolean(),
            'content' => Faker::randomString(mt_rand(100, 300)),
            'content_short' => Faker::randomString(mt_rand(30, 50)),
            'status' => Faker::randomInArray(['deleted', 'published', 'draft']),
            'forecast' => mt_rand(100, 9999) / 100,
            'image_uri' => 'https://via.placeholder.com/400x300',
            'importance' => mt_rand(1, 3),
            'pageviews' => mt_rand(10000, 999999),
            'reviewer' => Faker::randomString(mt_rand(5, 10)),
            'timestamp' => Faker::randomDateTime()->getTimestamp(),
            'type' => Faker::randomInArray(['US', 'VI', 'JA']),

        ];

        return response()->json(new JsonResponse($article));
    });

    Route::get('articles/{id}/pageviews', function ($id) {
        $pageviews = [
            'PC' => mt_rand(10000, 999999),
            'Mobile' => mt_rand(10000, 999999),
            'iOS' => mt_rand(10000, 999999),
            'android' => mt_rand(10000, 999999),
        ];
        $data = [];
        foreach ($pageviews as $device => $pageview) {
            $data[] = [
                'key' => $device,
                'pv' => $pageview,
            ];
        }

        return response()->json(new JsonResponse(['pvData' => $data]));
    });

});
