#### component-require

```
composer require stru/stru-hyperf-oauth
```

#### tips
```
该组件配合 "stru/stru-hyperf-ui" 使用效果更佳  (提供界面组件，也可自己实现)
该组件配合 "stru/stru-hyperf-auth" 使用效果更佳 (提供登录验证组件，也可自己实现)
```

#### publish

```
php bin/hyperf.php vendor:publish stru/stru-hyperf-ouath
```

#### migrate

```
php bin/hyperf.php migrate
```

#### create authCode Client
```
php bin/hyperf.php stru:client
```

#### create rsa key
```
php bin/hyperf.php stru:keys
```

#### use
```
// 客户端----laravel控制器效果

protected $url = 'http://192.168.10.1:8000/';           //客户端地址
protected $remoteUrl = 'http://192.168.10.10:9501/';    //oauth服务器地址
public function redirect(Request $request){
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => 1,
        'redirect_uri' => $this->url.'callback',
        'response_type' => 'code',
        'scope' => 'public',
        'state' => $state,
    ]);

    return redirect($this->remoteUrl.'oauth/authorize?'.$query);
}

public function callback(Request $request)
{
    $http = new Client();

    $response = $http->post($this->remoteUrl.'oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => 1,
            'client_secret' => 'QI7aUla1qVTVFqYRStqt5D56sR0s0L5HU0NS1YMG',
            'redirect_uri' => $this->url.'callback',
            'code' => $request->code,
        ],
    ]);

    return \json_decode((string) $response->getBody(), true);
}
```
