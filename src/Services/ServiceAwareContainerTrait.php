<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar\Services;


use Phulsar\Contract\DependencyContainerInterface;
use Phulsar\Contract\ServiceInterface;

trait ServiceAwareContainerTrait
{
    /**
     * enforces the provided value.
     *
     * @param $value
     * @return mixed
     */
    protected function enforceValue($value)
    {
        if ( $value instanceof \Closure ) {
            return $this->enforceCallable($value);
        }

        if ( $value instanceof ServiceInterface ) {
            return $value->marshal($this->getDependencyContainer());
        }

        return $value;
    }

    /**
     * enforces the provided callback.
     *
     * @param callable $callback
     * @return mixed
     */
    abstract protected function enforceCallable(callable $callback);

    /**
     * returns the dependency container.
     *
     * @return DependencyContainerInterface
     */
    abstract public function getDependencyContainer(): DependencyContainerInterface;
}
