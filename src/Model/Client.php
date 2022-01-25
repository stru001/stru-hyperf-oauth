<?php


namespace Stru\StruHyperfOauth\Model;


use Hyperf\Database\Model\Model;
use Stru\StruHyperfOauth\StruOauth;

class Client extends Model
{
    protected $table = 'oauth_clients';

    protected $fillable = [];

    protected $guarded = [];

    protected $hidden = ['secret'];

    protected $casts = [
        'grant_types' => 'array',
        'personal_access_client' => 'bool',
        'password_client' => 'bool',
        'revoked' => 'bool',
    ];

    public $plainSecret;

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

    public function getPlainSecretAttribute()
    {
        return $this->plainSecret;
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

    public function firstParty()
    {
        return $this->personal_access_client || $this->password_client;
    }

    public function skipAuthorization()
    {
        return false;
    }

    public function confidential()
    {
        return ! empty($this->secret);
    }

    public function getKeyType()
    {
        return StruOauth::clientUuids() ? 'string' : $this->keyType;
    }

    public function getIncrementing()
    {
        return StruOauth::clientUuids() ? false : $this->incrementing;
    }

}