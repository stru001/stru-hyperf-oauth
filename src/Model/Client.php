<?php


namespace Stru\StruHyperfOauth\Model;


use Hyperf\Database\Model\Model;
use Stru\StruHyperfOauth\StruOauth;

class Client extends Model
{
    protected $table = 'oauth_clients';

    protected $fillable = [];

    protected $hidden = ['secret'];

    public function user()
    {
        return $this->belongsTo(App\Model\User::class);
    }

    public function authCodes()
    {
        return $this->hasMany(StruOauth::authCodeModel(),'client_id');
    }

    public function tokens()
    {
        return $this->hasMany(StruOauth::tokenModel(),'client_id');
    }
}