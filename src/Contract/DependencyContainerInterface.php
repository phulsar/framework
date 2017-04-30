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
     * checks whether the provided interface is known to the current container.
     *
     * @param string $interface
     * @return bool
     */
    public function knows(string $interface): bool;

    /**
     * forks the current container. Optionally wraps further orchestration of the container fork into the optionally
     * provided callback. A fork shares the same reflection cache as the current container.
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
     * @param string[] $optionalParameters
     * @return mixed
     */
    public function call(callable $callback, array $arguments = [], array $optionalParameters = []);

    /**
     * marshals the instance for the provided interface. Optionally provided arguments supersede orchestrated
     * parameters at the service registration for this instance.
     *
     * @param string $interface
     * @param array $arguments
     * @param string[] $optionalParameters
     * @return mixed
     */
    public function make(string $interface, array $arguments = [], array $optionalParameters = []);

    /**
     * marshals a fresh instance for the provided interface. Optionally provided arguments supersede orchestrated
     * parameters at the service registration for this instance.
     *
     * @param string $interface
     * @param array $arguments
     * @param string[] $optionalParameters
     * @return mixed
     */
    public function fresh(string $interface, array $arguments = [], array $optionalParameters = []);

    /**
     * returns the reflection cache of the current container.
     *
     * @return ReflectionCacheInterface
     */
    public function getReflectionCache(): ReflectionCacheInterface;
}
