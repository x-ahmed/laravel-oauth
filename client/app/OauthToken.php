<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthToken extends Model
{
    /** @var array $guarded */
    protected $guarded = [];

    /**
     * check whether the users access token is expired or not
     *
     * @return bool
     **/
    public function isExpired(): bool
    {
        return now()->gte($this->updated_at->addSeconds($this->expires_in));
    }
}
