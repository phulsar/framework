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


interface ServiceInterface extends ServiceMethodInterface
{
    /**
     * wraps the service orchestration into a callback.
     *
     * @param callable $callback
     * @return ServiceInterface
     */
    public function enclose(callable $callback): ServiceInterface;

    /**
     * returns the name of the interface.
     *
     * @return string
     */
    public function getInterface(): string;

    /**
     * sets the factory of the service. This method must remove any concrete setting and stored instance from
     * the service instance.
     *
     * @param callable $callback
     * @return ServiceInterface
     */
    public function withFactory(callable $callback): ServiceInterface;

    /**
     * sets the concrete of the service. This method must remove any factory and stored instance form the service
     * instance.
     *
     * @param object|string $concrete
     * @return ServiceInterface
     */
    public function withConcrete($concrete): ServiceInterface;

    /**
     * sets the concrete class of the service. This method must remove any factory and stored instance from the service
     * instance.
     *
     * @param string $concrete
     * @return ServiceInterface
     */
    public function withConcreteClass(string $concrete): ServiceInterface;

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
    public function withMethod(string $method, callable $callback = null): ServiceInterface;

    /**
     * sets the value for a given parameter.
     *
     * @param string $parameter
     * @param null $value
     * @return ServiceMethodInterface|ServiceInterface
     */
    public function withParameter(string $parameter, $value = null): ServiceMethodInterface;

    /**
     * ensures that the provided optional parameters are fulfilled.
     *
     * @param \string[] ...$parameters
     * @return ServiceMethodInterface|ServiceInterface
     */
    public function withOptionalParameters(string ... $parameters): ServiceMethodInterface;

    /**
     * sets the singleton state of the service.
     *
     * This method must remove any instance from the service instance.
     *
     * @param bool $switch
     * @return ServiceInterface
     */
    public function singleton(bool $switch = true): ServiceInterface;

    /**
     * marshals the service instance.
     *
     * @param DependencyContainerInterface $dependencyContainer
     * @param array $arguments
     * @param array $optionalArguments
     * @return mixed
     */
    public function marshal(DependencyContainerInterface $dependencyContainer, array $arguments = [], $optionalArguments = []);

    /**
     * marshals a new service instance, regardless if the service utilizes singleton.
     *
     * @param DependencyContainerInterface $dependencyContainer
     * @param array $arguments
     * @param array $optionalArguments
     * @return mixed
     */
    public function marshalFresh(DependencyContainerInterface $dependencyContainer, array $arguments = [], $optionalArguments = []);
}
