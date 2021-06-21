<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;

class OauthController extends Controller
{
    /** @var string $url */
    private $url;

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

    public function callback(Request $request)
    {
        dd($request->all());
    }
}
