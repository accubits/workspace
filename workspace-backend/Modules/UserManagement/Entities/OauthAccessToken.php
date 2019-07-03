<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    protected $fillable = [];

    protected $table = 'oauth_access_tokens';
}
