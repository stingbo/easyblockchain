<?php

namespace EasyBlockchain\Eth;

use EasyBlockchain\Eth\Kernel\BaseClient;

class Client extends BaseClient
{
    public function get(string $uri, array $param = [])
    {
        return $this->httpGet($uri, $param);
    }

    public function postJson(string $uri, array $params = [])
    {
        return $this->httpPostJson($uri, $params);
    }
}
