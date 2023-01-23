## PHP Easy Blockchain Api

## Requirement

1. PHP >= 7.4
2. **[Composer](https://getcomposer.org/)**

## Installation

```shell
$ composer require "stingbo/easyblockchain" -vvv
```

## Usage

### Tron
<details>
<summary><b> :rocket: Quick Start </b></summary>

```php
<?php

use EasyBlockchain\Factory;

$config = [
    'tron' => [
        'response_type' => 'array',
        'base_uri' => 'http://127.0.0.1:8090',
        'app_key' => 'YOUR TRON API KEY',
        'app_key_uri' => 'https://api.trongrid.io',
        'log' => [
            'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
            'channels' => [
                'dev' => [ // 测试环境
                    'driver' => 'daily',
                    'path' => '/tmp/tron.log',
                    'level' => 'debug',
                    'days' => 60,
                ],
                'prod' => [ // 生产环境
                    'driver' => 'daily',
                    'path' => '/tmp/tron.log',
                    'level' => 'debug',
                    'days' => 90,
                ],
            ],
        ],
    ],
];

// 调用通用API
$app = Factory::tron($config['tron']);
$app->client->get('/wallet/generateaddress');

// 调用Trongrid API，有APIKEY标识，则会自动在header里带上此参数，并调用app_key_uri的地址
$block_number = 88888;
$data = $app->client->get("/v1/blocks/{$block_number}/events", [
    'only_confirmed' => $params['only_confirmed'] ?? true,
    'limit' => $params['limit'] ?? 100,
    'fingerprint' => $params['fingerprint'] ?? '',
], 'APIKEY');
```
</details>
