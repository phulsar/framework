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
    protected $booted = false;

    public function __construct(array $settings)
    {
        $settings['debug'] = $settings['debug'] ?? false;
        $settings['booted'] = false;

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
        if ( $this->get('booted') ) {
            throw new BootstrapException('Application already booted');
        }

        if ( ! $this->getDependencyContainer()->knows(ApplicationKernelInterface::class) ) {
            $this
                ->getDependencyContainer()
                ->service(ApplicationKernelInterface::class, TraditionalKernel::class)
                ->singleton(true)
            ;
        }
        else {
            $this
                ->getDependencyContainer()
                ->service(ApplicationKernelInterface::class)
                ->singleton(true)
            ;
        }

        $kernel = $this->getDependencyContainer()->make(ApplicationKernelInterface::class);

        /** @var ApplicationKernelInterface $kernel */
        $kernel->boot($this);

        $this->items['booted'] = true;
    }

    public function register(ProviderInterface ... $providers)
    {

    }

    public function run()
    {
        if ( ! $this->get('booted') ) {
            $this->boot();
        }

        $this->getDependencyContainer()->make(ApplicationKernelInterface::class)->run($this);
    }
}