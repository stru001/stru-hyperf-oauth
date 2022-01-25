# component-require

```
composer require stru/stru-hyperf-oauth
composer require hyperf/view
composer require hyperf/view-engine
composer require hyperf/task
composer require duncan3dc/blade
```

# publish

```
php bin/hyperf.php vendor:publish stru/stru-hyperf-ouath
php bin/hyperf.php vendor:publish hyperf/view
php bin/hyperf.php vendor:publish hyperf/translation
php bin/hyperf.php vendor:publish hyperf/validation
```

# migrate

```
php bin/hyperf.php migrate
```

# create authCode Client
```
php bin/hyperf.php stru:client
```

# create rsa key
```
php bin/hyperf.php stru:keys
```

# view config
```
# config/server.php 增加如下内容
'settings' => [
    // 静态资源
    'task_worker_num' => 1,
    'task_enable_coroutine' => false,
    'document_root' => BASE_PATH . '/public',
    'enable_static_handler' => true,
],
'callbacks' => [
    Event::ON_TASK => [Hyperf\Framework\Bootstrap\TaskCallback::class, 'onTask'],
    Event::ON_FINISH => [Hyperf\Framework\Bootstrap\FinishCallback::class, 'onFinish'],
]
# 验证器中间件配置 config/autoload/middlewares.php 
'http' => [
    \Hyperf\Validation\Middleware\ValidationMiddleware::class
]
# 异常处理器配置和 config/autoload/exceptions.php
'handler' => [
    'http' => [
        \Hyperf\Validation\ValidationExceptionHandler::class,
    ]
]
```
