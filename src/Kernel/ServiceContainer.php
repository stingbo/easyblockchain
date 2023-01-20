<?php

namespace EasyBlockchain\Kernel;

use EasyBlockchain\Kernel\Providers\ConfigServiceProvider;
use EasyBlockchain\Kernel\Providers\HttpClientServiceProvider;
use EasyBlockchain\Kernel\Providers\LogServiceProvider;
use Pimple\Container;

/**
 * Class ServiceContainer.
 *
 * @property Config $config
 */
class ServiceContainer extends Container
{
    /**
     * @var string
     */
    protected $id;

    protected array $providers = [];

    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * Constructor.
     */
    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        $this->userConfig = $config;

        parent::__construct($prepends);

        $this->registerProviders($this->getProviders());

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id ?? $this->id = md5(json_encode($this->userConfig));
    }

    /**
     * Magic get access.
     *
     * @return mixed
     */
    public function __get(string $id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param mixed $value
     */
    public function __set(string $id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param string $id
     * @param mixed  $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetSet($id, $value);
    }

    public function getConfig(): array
    {
        $base = [
            'http' => [
                'timeout' => 30.0,
                'base_uri' => $this->userConfig['base_uri'],
            ],
        ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * Return all providers.
     */
    public function getProviders(): array
    {
        return array_merge([
            ConfigServiceProvider::class,
            HttpClientServiceProvider::class,
            LogServiceProvider::class,
        ], $this->providers);
    }

    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}
