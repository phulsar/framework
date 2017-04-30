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


use Phulsar\Application;
use Phulsar\Contract\ApplicationKernelInterface;
use Phulsar\Stack\HttpKernelInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TraditionalKernel implements HttpKernelInterface, ApplicationKernelInterface
{
    /**
     * boots the kernel under the circumstances of the actual application container.
     *
     * @param Application $application
     * @return mixed
     */
    public function boot(Application $application)
    {
        $services = $application->getDependencyContainer();

        if ( ! $services->knows(HttpKernelInterface::class) ) {
            $services->service(HttpKernelInterface::class)->withConcrete($this)->singleton();
        }
    }

    /**
     * runs the kernel under the circumstances of the actual application container.
     *
     * @param Application $application
     * @return mixed
     */
    public function run(Application $application)
    {
        // TODO: Implement run() method.
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