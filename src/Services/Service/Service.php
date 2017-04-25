<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar\Services\Service;


use Phulsar\Contract\DependencyContainerInterface;
use Phulsar\Contract\ServiceInterface;
use Phulsar\Contract\ServiceMethodInterface;
use Phulsar\Exception\Container\InvalidArgumentException;
use Phulsar\Services\ServiceIncubationUtilityTrait;

/**
 * Class Service
 * @package Phulsar\Services\Service
 */
class Service extends ServiceMethod implements ServiceInterface
{
    use ServiceIncubationUtilityTrait;

    /**
     * @var string
     */
    protected $interface;

    /**
     * @var string|null
     */
    protected $concrete;

    /**
     * @var object|null
     */
    protected $instance;

    /**
     * @var callable|null
     */
    protected $factory;

    /**
     * @var ServiceMethodInterface[]
     */
    protected $methods = [];

    /**
     * @var bool
     */
    protected $singleton = false;

    /**
     * Service constructor.
     * @param string $interface
     */
    public function __construct(string $interface)
    {
        $this->interface = $interface;
    }

    /**
     * wraps the service orchestration into a callback.
     *
     * @param callable $callback
     * @return ServiceInterface
     */
    public function enclose(callable $callback): ServiceInterface
    {
        call_user_func($callback, $this);

        return $this;
    }

    /**
     * returns the name of the interface.
     *
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * sets the factory of the service. This method must remove any concrete setting and stored instance from
     * the service instance.
     *
     * @param callable $callback
     * @return ServiceInterface
     */
    public function withFactory(callable $callback): ServiceInterface
    {
        $this->instance = $this->concrete = null;
        $this->factory = $callback;

        return $this;
    }

    /**
     * sets the concrete of the service. This method must remove any factory and stored instance form the service
     * instance.
     *
     * @param object|string $concrete
     * @return ServiceInterface
     */
    public function withConcrete($concrete): ServiceInterface
    {
        if ( $concrete instanceof \Closure ) {
            $this->instance = $this->concrete = null;
            $this->factory = $concrete;

            return $this;
        }

        $this->withConcreteClass(is_object($concrete) ? get_class($concrete) : $concrete);

        if ( is_object($concrete) ) {
            $this->instance = $concrete;
        }

        return $this;
    }

    /**
     * sets the concrete class of the service. This method must remove any factory and stored instance from the service
     * instance.
     *
     * @param string $concrete
     * @return ServiceInterface
     */
    public function withConcreteClass(string $concrete): ServiceInterface
    {
        $this->factory = $this->instance = null;

        if ( ! is_a($concrete, $this->getInterface(), true) ) {
            throw new InvalidArgumentException('concrete is not compatible with the serviced interface');
        }

        $this->concrete = $concrete;

        return $this;
    }

    /**
     * ensures that a method will be called with all mandatory parameters fulfilled. When a callback is provided as
     * the second parameter, the callback will receive a ServiceMethodInterface instance for the queried method.
     *
     * This method must remove any instance from the service instance.
     *
     * @param string $method
     * @param callable $callback
     * @return ServiceInterface
     */
    public function withMethod(string $method, callable $callback = null): ServiceInterface
    {
        $this->methods[$method] = $this->methods[$method] ?? new ServiceMethod();

        if ( is_callable($callback) ) {
            call_user_func($callback, $this->methods[$method]);
        }

        return $this;
    }

    /**
     * sets the singleton state of the service.
     *
     * This method must remove any instance from the service instance.
     *
     * @param bool $switch
     * @return ServiceInterface
     */
    public function singleton(bool $switch = true): ServiceInterface
    {
        $this->singleton = $switch;

        return $this;
    }

    /**
     * marshals the service instance.
     *
     * @param DependencyContainerInterface $dependencyContainer
     * @param array $arguments
     * @param array $optionalArguments
     * @return mixed
     */
    public function marshal(
        DependencyContainerInterface $dependencyContainer,
        array $arguments = [],
        $optionalArguments = []
    ) {
        if ( $this->singleton && is_object($this->instance) ) {
            return $this->instance;
        }

        $instance = $this->marshalFresh(
            $dependencyContainer,
            $arguments,
            $optionalArguments
        );

        if ( $this->singleton ) {
            $this->instance = $instance;
        }

        return $instance;
    }

    /**
     * marshals a new service instance, regardless if the service utilizes singleton.
     *
     * @param DependencyContainerInterface $dependencyContainer
     * @param array $arguments
     * @param string[] $optionalArguments
     * @return mixed
     */
    public function marshalFresh(
        DependencyContainerInterface $dependencyContainer,
        array $arguments = [],
        array $optionalArguments = []
    ) {
        $arguments = array_replace($this->values, $arguments);

        if ( $this->factory ) {
            $instance = $dependencyContainer->call($this->factory, $arguments);
        }
        else {
            $dependencies = $this->orchestrateParameters(
                $this->factory
                    ? $dependencyContainer->getReflectionCache()->reflectCallable($this->factory)
                    : $dependencyContainer->getReflectionCache()->reflectClassConstructor($this->concrete),
                $arguments,
                array_replace($this->optionalParameters, $optionalArguments),
                $dependencyContainer
            );

            $class = $this->concrete;
            $instance = new $class(... $dependencies);
        }

        foreach ( $this->methods as $method => $object ) {
            $dependencyContainer->call([$instance, $method], $object->getParameters(), $object->getOptionalParameters());
        }

        return $instance;
    }
}
