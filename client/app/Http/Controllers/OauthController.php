<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $this->clientUrl = route('oauth.callback');
        $this->serverUrl = config('app.server_url');
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
            'client_id'     => '1',                // the server vue UI created client id
            'redirect_uri'  => $this->clientUrl,
            'response_type' => 'code',
            'scope'         => '*',
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
     * @return Illuminate\Http\Response
     **/
    public function callback(Request $request): Response
    {
        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::post("{$this->serverUrl}/oauth/token", [
            'grant_type'    => 'authorization_code',
            'client_id'     => '1',                                          // the server vue UI created client id
            'client_secret' => 'iITOfVD5KD3luEzxLC6CMgoMswPukRkUrQTcK9fU',   // the server vue UI created client secret
            'redirect_uri'  => $this->clientUrl,
            'code'          => $request->code,
        ]);
        
        dd($response->json());
    }
}
