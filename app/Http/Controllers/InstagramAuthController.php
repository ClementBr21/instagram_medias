<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class InstagramAuthController
{
    public function authenticate()
    {
        if(!Cache::has('access_token')) {
            $response = $this->generateAccessToken();
            $response = $this->generateLongAccessToken($response['access_token']);

            Cache::put('access_token', $response['access_token'], now()->addSeconds($response['expires_in']));
        }

        return Redirect::to('/instagram');
    }

    private function generateAccessToken()
    {
        $response = Http::asForm()->post('https://api.instagram.com/oauth/access_token', [
            'code' => request()->code,
            'client_id' => config('instagram.client_id'),
            'client_secret' => config('instagram.client_secret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => secure_url('auth')
        ]);

        if($response->failed()){
            abort(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    private function generateLongAccessToken(string $access_token)
    {
        $response = Http::asForm()->get('https://graph.instagram.com/access_token', [
            'grant_type' =>'ig_exchange_token',
            'client_secret' => config('instagram.client_secret'),
            'access_token' => $access_token
        ]);

        if($response->failed()){
            abort(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    public function getInstagramAuthorizationCode()
    {
        $client_id = config('instagram.client_id');
        $redirect_uri = secure_url('auth');
        $response_type = 'code';
        $scope = 'user_profile,user_media';

        $response = Http::get('https://api.instagram.com/oauth/authorize', [
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => $response_type,
            'scope' => $scope
        ]);

        var_dump($response->json());
        if($response->status() === 200){
            return Redirect::to("https://api.instagram.com/oauth/authorize?client_id={$client_id}&redirect_uri={$redirect_uri}&response_type={$response_type}&scope={$scope}");
        }

        abort(Response::HTTP_UNAUTHORIZED, 'Can\'t connect to instagram authorize api');
    }
}
