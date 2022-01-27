<?php


namespace Stru\StruHyperfOauth\Model;


use Hyperf\Database\Model\Model;

class AccessToken extends Model
{
    protected $table = 'oauth_access_tokens';

    protected $fillable = [];

    protected $hidden = [];
}