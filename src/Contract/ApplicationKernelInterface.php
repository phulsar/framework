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


use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

interface ApplicationKernelInterface
{
    /**
     * ApplicationKernelInterface constructor.
     * @param DependencyContainerInterface $dependencyContainer
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(
        DependencyContainerInterface $dependencyContainer,
        ContainerInterface $container,
        LoggerInterface $logger
    );

    /**
     * registers providers to the application.
     *
     * @param ProviderInterface[] ...$providers
     * @return mixed
     */
    public function register(ProviderInterface ... $providers);
}