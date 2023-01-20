<?php

namespace EasyBlockchain\Kernel;

use EasyBlockchain\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LogLevel;

class BaseClient
{
    use HasHttpRequests {
        request as performRequest;
    }

    /**
     * @var \EasyBlockchain\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var null
     */
    protected $sign_type = 'NONE';

    /**
     * BaseClient constructor.
     *
     * @param \EasyBlockchain\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * @param bool $returnRaw
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyBlockchain\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyBlockchain\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * GET request.
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyBlockchain\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyBlockchain\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpGet(string $url, array $query = [], string $sign_type = 'NONE')
    {
        $this->sign_type = $sign_type;

        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * POST request.
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyBlockchain\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyBlockchain\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpPost(string $url, array $data = [], string $sign_type = 'NONE')
    {
        $this->sign_type = $sign_type;

        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyBlockchain\Kernel\Support\Collection|array|object|string
     *
     * @throws \EasyBlockchain\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpPostJson(string $url, array $data = [], array $query = [], string $sign_type = 'NONE')
    {
        $this->sign_type = $sign_type;

        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }

    /**
     * Patch request.
     *
     * @return array|Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpPatch(string $url, array $data = [], array $query = [], string $sign_type = 'NONE')
    {
        $this->sign_type = $sign_type;

        return $this->request($url, 'PATCH', ['query' => $query, 'json' => $data]);
    }

    /**
     * DELETE request.
     *
     * @return array|Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpDelete(string $url, array $query = [], string $sign_type = 'NONE')
    {
        $this->sign_type = $sign_type;

        return $this->request($url, 'DELETE', ['query' => $query]);
    }

    /**
     * 增加header.
     *
     * @return \Closure
     */
    protected function addHeaderMiddleware($header, $value)
    {
        return function (callable $handler) use ($header, $value) {
            return function (RequestInterface $request, array $options) use ($handler, $header, $value) {
                $request = $request->withHeader($header, $value);

                return $handler($request, $options);
            };
        };
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        $formatter = new MessageFormatter($this->app['config']['http.log_template'] ?? MessageFormatter::DEBUG);

        return Middleware::log($this->app['logger'], $formatter, LogLevel::DEBUG);
    }

    /**
     * proxy request.
     *
     * @return \Closure
     */
    protected function proxyMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $proxy_config = $this->app->config->get('proxy');
                if ($proxy_config && is_array($proxy_config)) {
                    $options['proxy'] = $proxy_config;
                }

                return $handler($request, $options);
            };
        };
    }
}
