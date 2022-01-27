<?php


namespace Stru\StruHyperfOauth\Model;


use Hyperf\Database\Model\Model;
use Stru\StruHyperfOauth\StruOauth;

class Client extends Model
{
    protected $table = 'oauth_clients';

    protected $fillable = [];

    protected $hidden = [];

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

    public function setSecretAttribute($value)
    {
        $this->plainSecret = $value;

        if (is_null($value) || ! StruOauth::$hashesClientSecrets) {
            $this->attributes['secret'] = $value;

            return;
        }

        $this->attributes['secret'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public function getSecretAttribute()
    {
        return $this->plainSecret;
    }
}