<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar\Kernel;


use Phulsar\Contract\ApplicationKernelInterface;
use Phulsar\Contract\DependencyContainerInterface;
use Phulsar\Contract\ProviderInterface;
use Phulsar\Stack\HttpKernelInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class TraditionalKernel implements HttpKernelInterface, ApplicationKernelInterface
{
    protected $container;
    protected $services;
    protected $logger;

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
    ) {
        $this->services = $dependencyContainer;
        $this->container = $container;
        $this->logger = $logger;
    }


    /**
     * registers providers to the application.
     *
     * @param ProviderInterface[] ...$providers
     * @return mixed
     */
    public function register(ProviderInterface ... $providers)
    {
        // TODO: Implement register() method.
    }

    /**
     * Handles a request to response transformation.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
    }

}