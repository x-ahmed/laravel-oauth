<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;

class OauthController extends Controller
{
    /**
     * Construct new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->clientUrl    = route('oauth.callback');
        $this->serverUrl    = config('app.server_url');
        $this->clientId     = config('app.client_id');
        $this->clientSecret = config('app.client_secret');
    }

    /**
     * Redirect users to oauth server
     *
     * https://laravel.com/docs/7.x/passport#requesting-tokens
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Routing\Redirector|Illuminate\Http\RedirectResponse
     **/
    public function redirect(Request $request): Redirector | RedirectResponse
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id'     => $this->clientId,    // the server vue UI created client id
            'redirect_uri'  => $this->clientUrl,
            'response_type' => 'code',
            'scope'         => 'view-posts',       // https://laravel.com/docs/7.x/passport#assigning-scopes-to-tokens
            'state'         => $state,
        ]);

        return redirect("{$this->serverUrl}/oauth/authorize?{$query}");
    }

    /**
     * Call authorization token back to the client
     *
     * https://laravel.com/docs/7.x/passport#requesting-tokens
     *      : Converting Authorization Codes To Access Tokens
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Routing\Redirector|Illuminate\Http\RedirectResponse
     * @throws InvalidArgumentException
     **/
    public function callback(Request $request): Redirector | RedirectResponse
    {
        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::post("{$this->serverUrl}/oauth/token", [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,        // the server vue UI created client id
            'client_secret' => $this->clientSecret,    // the server vue UI created client secret
            'redirect_uri'  => $this->clientUrl,
            'code'          => $request->code,
        ]);

        $response = $response->json();

        $request->user()->token()->delete();
        $request->user()->token()->create([
            'access_token'  => $response['access_token'],
            'refresh_token' => $response['refresh_token'],
            'expires_in'    => $response['expires_in'],
        ]);

        return redirect('/home');
    }
}
