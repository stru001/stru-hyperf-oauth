<?php


namespace Stru\StruHyperfOauth\Model;


use Hyperf\Database\Model\Model;
use Stru\StruHyperfOauth\StruOauth;

class AuthCode extends Model
{
    protected $table = 'oauth_auth_codes';

    protected $fillable = [];

    protected $hidden = ['secret'];

}