<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar\Services;


use Phulsar\Contract\DependencyContainerInterface;
use Phulsar\Contract\VariadicParameterInterface;
use Phulsar\Exception\Container\UnresolvableDependencyException;

trait ServiceIncubationUtilityTrait
{
    /**
     * orchestrates parameters by the provided reflection parameter iterator, the provided injectable arguments and
     * the provided resolvable parameters using the provided dependency container.
     *
     * @param \Iterator $parameters
     * @param array $arguments
     * @param array $resolvableParameters
     * @param DependencyContainerInterface $container
     * @return \Generator
     */
    protected function orchestrateParameters(
        \Iterator $parameters,
        array $arguments,
        array $resolvableParameters,
        DependencyContainerInterface $container
    ): \Generator {
        foreach ( $parameters as $current ) {
            yield from $this->orchestrateSingleParameter($current, $arguments, $resolvableParameters, $container);
        }
    }

    /**
     * orchestrates the provided reflection parameter by the provided injectable arguments and the provided
     * resolvable parameters using the provided dependency container.
     *
     * @param \ReflectionParameter $parameter
     * @param array $arguments
     * @param array $resolvableParameters
     * @param DependencyContainerInterface $container
     * @return \Generator
     * @throws UnresolvableDependencyException
     */
    protected function orchestrateSingleParameter(
        \ReflectionParameter $parameter,
        array $arguments,
        array $resolvableParameters,
        DependencyContainerInterface $container
    ): \Generator {
        if ( array_key_exists($parameter->getName(), $arguments) ) {
            yield from $this->negotiateArgument($arguments[$parameter->getName()]);
        }
        else if ( array_key_exists($parameter->getPosition(), $arguments) ) {
            yield from $this->negotiateArgument($arguments[$parameter->getPosition()]);
        }
        else if (
            $parameter->isOptional() &&
            in_array($parameter->getName(), $resolvableParameters) &&
            $class = $parameter->getClass()
        ) {
            yield $container->make($class->getName());
        }
        else if ( $parameter->isOptional() && $parameter->isDefaultValueAvailable() ) {
            yield $parameter->getDefaultValue();
        }
        else {
            throw new UnresolvableDependencyException(
                'Can not resolve parameter: '.$parameter->getName()
            );
        }
    }

    /**
     * negotiates the provided arguments.
     *
     * @param $argument
     * @return \Generator
     */
    protected function negotiateArgument($argument): \Generator
    {
        if ( $argument instanceof VariadicParameterInterface ) {
            yield from $argument->getIterator();
        }

        yield $argument;
    }
}