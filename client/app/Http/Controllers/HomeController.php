<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $serverUrl   = config('app.server_url');
        $accessToken = auth()->user()->token?->access_token;

        if (!$accessToken) {
            return view('home', ['posts' => []]);
        }

        if (auth()->user()->token->isExpired()) {
            return redirect()->route('oauth.refresh');
        }

        $response = Http::withHeaders([
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => "Bearer {$accessToken}",
        ])->get("{$serverUrl}/api/posts");

        if ($response->failed()) {
            return view('home', ['posts' => []]);
        }

        $posts = new Collection();
        foreach ($response->json() as $post) {
            $posts->push((object)$post);
        }

        return view('home', \compact('posts'));
    }
}
