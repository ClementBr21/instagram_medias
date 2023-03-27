<?php

namespace App\Console\Commands;

use App\Events\NewPostDetected;
use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class getNewInstagramPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-new-instagram-post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect if a new instagram had been post';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if(!Cache::has('access_token') && !Cache::has('last_post_date')) {
            return ;
        }

        $response = Http::get('https://graph.instagram.com/me/media', [
            'fields' => 'id,caption,permalink,media_type,media_url,timestamp,children',
            'limit' => 1,
            'access_token' => Cache::get('access_token'),
            'since' => Cache::get('last_post_date')
        ]);

        if($response->failed()){
            abort(Response::HTTP_BAD_REQUEST);
        }

        if(count($response['data'])){
            NewPostDetected::dispatch('De nouveaux posts sont disponibles', route('instagram'));
        }
    }
}
