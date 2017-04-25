<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar\Contract;


interface DependencyContainerInterface
{
    /**
     * orchestrates the provided interface as a common service.
     *
     * @param string $interface
     * @param null $concrete
     * @return ServiceInterface
     */
    public function service(string $interface, $concrete = null): ServiceInterface;

    /**
     * orchestrates the provided interface as a factory service.
     *
     * @param string $interface
     * @param callable $callback
     * @return ServiceInterface
     */
    public function factory(string $interface, callable $callback): ServiceInterface;

    /**
     * forks the current container. Optionally wraps further orchestration of the container fork into the optionally
     * provided callback.
     *
     * @param callable|null $callback
     * @return DependencyContainerInterface
     */
    public function fork(callable $callback = null): DependencyContainerInterface;

    /**
     * calls the provided callback and fulfills all dependencies considering the currently registered items.
     *
     * @param callable $callback
     * @param array $arguments
     * @return mixed
     */
    public function call(callable $callback, array $arguments = []);

    /**
     * marshals the instance for the provided interface. Optionally provided arguments supersede orchestrated
     * parameters at the service registration for this instance.
     *
     * @param string $interface
     * @param array $arguments
     * @return mixed
     */
    public function make(string $interface, array $arguments = []);

    /**
     * @param string $interface
     * @param array $arguments
     * @return mixed
     */
    public function fresh(string $interface, array $arguments = []);

    /**
     * returns the reflection cache of the current container.
     *
     * @return ReflectionCacheInterface
     */
    public function getReflectionCache(): ReflectionCacheInterface;
}
