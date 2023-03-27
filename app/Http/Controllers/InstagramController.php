<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class InstagramController
{
    public function getMedias(Request $request)
    {
        if(!Cache::has('access_token')) {
            return $this->redirectToAuth();
        }

        $response = Http::get('https://graph.instagram.com/me/media', $this->getApiMediaParameters($request));

        if($response->failed()){
            abort(Response::HTTP_BAD_REQUEST);
        }

        $this->storeLastPostDate($response);

        return view('instagram', [
            'medias' => $response['data'],
            'before' => array_key_exists('previous', $response['paging']) ? $response['paging']['cursors']['before'] : null,
            'after' => array_key_exists('next', $response['paging']) ? $response['paging']['cursors']['after'] : null
        ]);
    }

    private function getApiMediaParameters(Request $request): array
    {
        $parameters = [
            'fields' => 'id,caption,permalink,media_type,media_url,timestamp,children',
            'limit' => config('instagram.medias.per_page'),
            'access_token' => Cache::get('access_token')
        ];

        if($request->filled('after')){
            $parameters['after'] = $request->after;
        }

        if($request->filled('before')){
            $parameters['before'] = $request->before;
        }

        return $parameters;
    }

    private function redirectToAuth(): ?RedirectResponse
    {
        $authController = new InstagramAuthController();
        return $authController->getInstagramAuthorizationCode();
    }

    private function storeLastPostDate(\Illuminate\Http\Client\Response $response): void
    {
        if(count($response['data']) > 0){
            if($response['data'][0]['timestamp'] > Cache::get('last_post_date')){
                Cache::put('last_post_date', $response['data'][0]['timestamp']);
            }
        }

        return ;
    }
}
