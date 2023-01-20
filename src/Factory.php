<?php

/*
 * This file is part of the stingbo/easyblockchain.
 *
 * (c) sting bo <lianbo.wan@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyBlockchain;

use EasyBlockchain\Kernel\ServiceContainer;

/**
 * Class Factory.
 *
 * @method static \EasyBlockchain\Tron\Application tron(array $config)
 * @method static \EasyBlockchain\Eth\Application  eth(array $config)
 */
class Factory
{
    public static function make(string $name, array $config): ServiceContainer
    {
        $namespace = Kernel\Support\Str::studly($name);
        $application = "\\EasyBlockchain\\{$namespace}\\Application";

        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): ServiceContainer
    {
        return self::make($name, ...$arguments);
    }
}
