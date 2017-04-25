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
use Phulsar\Contract\ApplicationKernelInterface;
use Phulsar\Contract\DependencyContainerInterface;
use Phulsar\Contract\ProviderInterface;
use Phulsar\Exception\BootstrapException;
use Phulsar\Exception\ServerException;
use Phulsar\Kernel\TraditionalKernel;
use Phulsar\Services\ServiceAwareContainerTrait;
use Phulsar\Stack\HttpKernelInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Container
{
    use ServiceAwareContainerTrait;

    protected $dependencyContainer;
    protected $providers = [];
    protected $booted = false;

    public function __construct(array $settings)
    {
        $settings['debug'] = $settings['debug'] ?? false;
        $settings['kernel.class'] = $settings['kernel']['class'] ?? TraditionalKernel::class;

        $this->dependencyContainer = new DependencyContainer();

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

    public function boot()
    {
        if ( $this->booted ) {
            throw new BootstrapException('Application already booted');
        }

        $kernelClass = $this->get('kernel.class');
        $kernel = new $kernelClass($this->getDependencyContainer(), $this);

        if ( ! $kernel instanceof HttpKernelInterface ) {
            throw new ServerException('Provided Kernel does not support HttpKernelInterface');
        }

        if ( $kernel instanceof ApplicationKernelInterface ) {
            $kernel->register(... array_values($this->providers));
        }

        $this
            ->getDependencyContainer()
            ->service(HttpKernelInterface::class)
            ->singleton()
            ->withConcrete($kernel)
        ;

        $this->booted = true;
    }

    public function register(ProviderInterface ... $providers)
    {
        $providerCollection = array_combine(
            array_map(function(ProviderInterface $provider) {
                return get_class($provider);
            }, $providers),
            $providers
        );

        $this->providers = array_replace($this->providers, $providerCollection);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $kernel = $this->getDependencyContainer()->make(HttpKernelInterface::class);

        /** @var HttpKernelInterface $kernel */

        return $kernel->handle($request);
    }
}