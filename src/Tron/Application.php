<?php

namespace EasyBlockchain\Tron;

use EasyBlockchain\Kernel\ServiceContainer;

/**
 * Class Application.
 */
class Application extends ServiceContainer
{
    protected array $providers = [
       ServiceProvider::class,
    ];
}
