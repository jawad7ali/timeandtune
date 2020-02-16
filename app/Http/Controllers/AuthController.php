<?php
/**
 * File AuthController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Http\Controllers;

use App\Laravue\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        
        $credentials = $request->only('email', 'password');
        
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(new UserResource(Auth::user()), Response::HTTP_OK)->header('Authorization', $token);
        }

        return response()->json(new JsonResponse([], 'login_error'), Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json((new JsonResponse())->success([]), Response::HTTP_OK);
    }

    public function user()
    {
        return new UserResource(Auth::user());
    }

    /**
     * @return mixed
     */
    private function guard()
    {
        return Auth::guard();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mobile_login(Request $request)
    {
        $credentials = $request->only('phone_no', 'password');
       
        
        if ($token = $this->guard()->attempt($credentials)) {
             $record = new UserResource(Auth::user());
        
          //  print_r($record);

            return response()->json(['success' => 'User Login success!', 'data'=>$record,'token'=>$token], 200);
        //return response()->json(new UserResource(Auth::user()), Response::HTTP_OK)->header('Authorization', $token);
        } 
        

         return response()->json(new JsonResponse([], 'login_error'), 401);

       //  return response()->json(new JsonResponse([], 'login_error'), Response::HTTP_UNAUTHORIZED);
    }
}
