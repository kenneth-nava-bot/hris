<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use GuzzleHttp\Client;
use App\Contracts\IApiToken;
use Illuminate\Http\Request;
use App\Http\Requests\TokenRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class TokenController extends Controller
{
    public function user()
    {
        return response()->json([
            'message' => 'Successfully retrieved authenticated user.',
            'data' => auth()->user()
        ]);
    }

    public function get(TokenRequest $request)
    {
        $data = $request->validated();


        $user = User::where('email', $data['username'])
                    ->orWhere('username', $data['username'])
                    ->first();

        if($user != NULL)
        {
            if(auth()->attempt(['email' => $user->email, 'password' => $data['password']]))
            {
                return $this->generate($user->email, $data['password']);
            }

            return response()->json([
                'message' => __('auth.incorrect'),
                'errors' => [
                    'password' => __('auth.failed', ['field' => 'password'])
                ]
            ], 422);
        }


        return response()->json([
            'message' => __('auth.incorrect'),
            'errors' => [
                'username' => __('auth.failed', ['field' => 'username'])
            ]
        ], 422);
    }

    public function remove(User $user)
    {
       auth()->user()->tokens->each(function($token, $key)
       {
            $token->delete();
       });


        appLog('Logged_Out', auth()->user()->id);


       return response()->json([
            'message' => 'User successfully logged out.'
       ]);
    }

    protected function generate($username, $password)
    {
         try
        {
            $http = new Client();

            $response = $http->post(route('passport.token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('passport.password_grant_id'),
                    'client_secret' => config('passport.password_grant_secret'),
                    'username' => $username,
                    'password' => $password,
                    'scope' => ''
                ]
            ]);


            appLog('Logged_In', auth()->user()->id);


            return response()->json([
                'message' => __('auth.received'),
                'data' => json_decode($response->getBody())
            ]);
        }
        catch(\Exception $e)
        {
            abort(400, __('auth.oops'));
        }
    }
}