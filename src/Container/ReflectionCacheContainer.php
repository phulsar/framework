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


use Phulsar\Contract\ReflectionCacheInterface;
use Phulsar\Exception\Container\NotFoundException;

/**
 * Class ReflectionCacheContainer
 * @package Phulsar\Container
 */
class ReflectionCacheContainer implements ReflectionCacheInterface
{
    /**
     * @var array[]
     */
    protected $parameters = [];

    /**
     * reflects the parameter of the provided callback.
     *
     * @param callable $callback
     * @return \Generator
     */
    public function reflectCallable(callable $callback): \Generator
    {
        is_callable($callback, false, $target);

        if ( false !== strpos($target, '::') && $callback instanceof \Closure ) {
            $hash = 'clsr:'.spl_object_hash($callback);

            if ( ! array_key_exists($hash, $this->parameters) ) {
                $this->parameters[$hash] = (new \ReflectionFunction($callback))->getParameters();
            }

            yield from $this->parameters[$hash];
        }
        else if ( false !== strpos($target, '::') ) {
            yield from $this->reflectClassMethod($callback[0], $callback[1]);
        }
        else {
            $key = 'fnct:'.$callback;

            if ( ! array_key_exists($key, $this->parameters) ) {
                $this->parameters[$key] = (new \ReflectionFunction($callback))->getParameters();
            }

            yield from $this->parameters[$key];
        }
    }

    /**
     * reflects the constructor of the provided class.
     *
     * @param string $class
     * @return \Generator
     */
    public function reflectClassConstructor(string $class): \Generator
    {
        $key = strtolower($class).'::__construct';

        if ( ! array_key_exists($key, $this->parameters) ) {
            $class = new \ReflectionClass($class);

            if ( $constructor = $class->getConstructor() ) {
                $this->parameters[$key] = $constructor->getParameters();
            }
            else {
                $this->parameters[$key] = [];
            }
        }

        yield from $this->parameters[$key];
    }

    /**
     * reflects the provided method of the provided class.
     *
     * @param string|object $class
     * @param string $method
     * @throws NotFoundException
     * @return \Generator
     */
    public function reflectClassMethod($class, string $method): \Generator
    {
        $instance = new \ReflectionClass($class);

        $key = strtolower(
            (
                is_object($class)
                    ? (
                        $instance->isAnonymous()
                            ? spl_object_hash($class)
                            : get_class($class)
                    )
                    : $class
            ).'::'.$method
        );

        if ( ! $instance->hasMethod($method) ) {
            throw new NotFoundException('Unknown method: '.$method);
        }

        if ( ! array_key_exists($key, $this->parameters) ) {
            $method = $instance->getMethod($method);

            $this->parameters[$key] = $method->getParameters();
        }

        yield from $this->parameters[$key];
    }

    /**
     * clears the reflection cache.
     *
     * @return mixed
     */
    public function clear()
    {
        $this->parameters = [];
    }

}