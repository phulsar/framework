<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar\Container;


use Phulsar\Contract\DependencyContainerInterface;
use Phulsar\Contract\ReflectionCacheInterface;
use Phulsar\Contract\ServiceInterface;
use Phulsar\Services\Service\Service;
use Phulsar\Services\ServiceIncubationUtilityTrait;

class DependencyContainer implements DependencyContainerInterface
{
    use ServiceIncubationUtilityTrait;

    /**
     * @var ServiceInterface[]
     */
    protected $services = [];

    /**
     * @var ReflectionCacheInterface
     */
    protected $reflections;

    /**
     * DependencyContainer constructor.
     * @param ReflectionCacheInterface|null $reflections
     */
    public function __construct(ReflectionCacheInterface $reflections = null) {
        $this->reflections = $reflections ?? new ReflectionCacheContainer();
    }

    /**
     * orchestrates the provided interface as a common service.
     *
     * @param string $interface
     * @param null $concrete
     * @return ServiceInterface
     */
    public function service(string $interface, $concrete = null): ServiceInterface
    {
        $key = strtolower(trim($interface, "\\"));

        if ( ! array_key_exists($key, $this->services) ) {
            $this->services[$key] = new Service($interface);
        }

        if ( null !== $concrete ) {
            $this->services[$key]->withConcrete($concrete);
        }

        return $this->services[$key];
    }

    /**
     * orchestrates the provided interface as a factory service.
     *
     * @param string $interface
     * @param callable $callback
     * @return ServiceInterface
     */
    public function factory(string $interface, callable $callback): ServiceInterface
    {
        $closure = \Closure::fromCallable($callback);

        return $this->service($interface)->withConcrete($closure);
    }

    /**
     * checks whether the provided interface is known to the current container.
     *
     * @param string $interface
     * @return bool
     */
    public function knows(string $interface): bool
    {
        $key = strtolower(trim($interface, "\\"));

        return array_key_exists($key, $this->services);
    }

    /**
     * forks the current container. Optionally wraps further orchestration of the container fork into the optionally
     * provided callback. A fork shares the same reflection cache as the current container.
     *
     * @param callable|null $callback
     * @return DependencyContainerInterface
     */
    public function fork(callable $callback = null): DependencyContainerInterface
    {
        $class = get_called_class();

        $instance = new $class($this->reflections);

        if ( is_callable($callback) ) {
            $this->call($callback, [$instance]);
        }

        return $instance;
    }

    /**
     * calls the provided callback and fulfills all dependencies considering the currently registered items.
     *
     * @param callable $callback
     * @param array $arguments
     * @param string[] $optionalParameters
     * @return mixed
     */
    public function call(callable $callback, array $arguments = [], array $optionalParameters = [])
    {
        $dependencies = $this->orchestrateParameters(
            $this->reflections->reflectCallable($callback),
            $arguments,
            $optionalParameters,
            $this
        );

        return call_user_func($callback, ... $dependencies);
    }

    /**
     * marshals the instance for the provided interface. Optionally provided arguments supersede orchestrated
     * parameters at the service registration for this instance.
     *
     * @param string $interface
     * @param array $arguments
     * @param string[] $optionalParameters
     * @return mixed
     */
    public function make(string $interface, array $arguments = [], array $optionalParameters = [])
    {
        $key = strtolower(trim($interface, "\\"));
        $service = $this->services[$key] ?? new Service($interface);

        return $service->marshal($this, $arguments, $optionalParameters);
    }

    /**
     * marshals a fresh instance for the provided interface. Optionally provided arguments supersede orchestrated
     * parameters at the service registration for this instance.
     *
     * @param string $interface
     * @param array $arguments
     * @param string[] $optionalParameters
     * @return mixed
     */
    public function fresh(string $interface, array $arguments = [], array $optionalParameters = [])
    {
        $key = strtolower(trim($interface, "\\"));
        $service = $this->services[$key] ?? new Service($interface);

        return $service->marshalFresh($this, $arguments, $optionalParameters);
    }

    /**
     * returns the reflection cache of the current container.
     *
     * @return ReflectionCacheInterface
     */
    public function getReflectionCache(): ReflectionCacheInterface
    {
        return $this->reflections;
    }
}
