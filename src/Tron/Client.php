<?php

namespace EasyBlockchain\Tron;

use EasyBlockchain\Tron\Kernel\BaseClient;

class Client extends BaseClient
{
    public function get(string $url, array $query = [], string $sign_type = 'NONE')
    {
        return $this->httpGet($url, $query, $sign_type);
    }

    public function postJson(string $url, array $data = [], array $query = [], string $sign_type = 'NONE')
    {
        return $this->httpPostJson($url, $data, $query, $sign_type);
    }
}
