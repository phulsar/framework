<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar;


use Phulsar\Container\Container;
use Phulsar\Container\DependencyContainer;
use Phulsar\Contract\DependencyContainerInterface;
use Phulsar\Services\ServiceAwareContainerTrait;

class Application extends Container
{
    use ServiceAwareContainerTrait;

    protected $dependencyContainer;

    public function __construct(array $settings)
    {
        $this->dependencyContainer = new DependencyContainer();

        $settings['debug'] = $settings['debug'] ?? false;

        parent::__construct($settings);
    }

    /**
     * returns the dependency container.
     *
     * @return DependencyContainerInterface
     */
    public function getDependencyContainer(): DependencyContainerInterface
    {
        return $this->dependencyContainer;
    }
}