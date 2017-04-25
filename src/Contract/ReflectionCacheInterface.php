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


interface ReflectionCacheInterface
{
    /**
     * reflects the parameter of the provided callback.
     *
     * @param callable $callback
     * @return \Generator
     */
    public function reflectCallable(callable $callback): \Generator;

    /**
     * reflects the constructor of the provided class.
     *
     * @param string $class
     * @return \Generator
     */
    public function reflectClassConstructor(string $class): \Generator;

    /**
     * reflects the provided method of the provided class.
     *
     * @param string|object $class
     * @param string $method
     * @return \Generator
     */
    public function reflectClassMethod($class, string $method): \Generator;

    /**
     * clears the reflection cache.
     *
     * @return mixed
     */
    public function clear();
}
