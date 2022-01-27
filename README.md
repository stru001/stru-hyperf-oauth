#### component-require

```
composer require stru/stru-hyperf-oauth
```

#### tips
```
1. 该组件配合 "stru/stru-hyperf-ui" 组件使用可以免去自己写登录注册页码的麻烦
2. 该组件 依赖 "stru/stru-hyperf-auth" 组件中的部分功能，因此要使用该组件请务必安装 
3. 该组件目前只实现了auto_code_grant验证方式，其他方式可自行参考官网实例进行补充
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

该命令生成auth_code验证方式的客户端，需要手动输入name和回调地址
```

#### create rsa key
```
php bin/hyperf.php stru:keys
```

#### use
```
// 客户端----laravel做客户端代码示例

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
