<?php

namespace EasyBlockchain\Tron\Kernel;

class BaseClient extends \EasyBlockchain\Kernel\BaseClient
{
    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        $sign_type = $this->sign_type ?? 'NONE';
        if ('APIKEY' == $sign_type) {
            $this->pushMiddleware($this->addHeaderMiddleware('TRON_PRO_API_KEY', $this->app->config->get('app_key')), 'add_header');
            $this->baseUri = $this->app->config->get('app_key_uri');
        }

        // proxy
        $this->pushMiddleware($this->proxyMiddleware(), 'proxy');

        // log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }
}
