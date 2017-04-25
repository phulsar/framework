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


use Phulsar\Exception\Container\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface, \Countable
{
    protected $items = [];

    /**
     * Container constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if ( ! $this->has($id) ) {
            throw new NotFoundException('Unknown identifier: '.$id);
        }

        return $this->items[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists($id, $this->items);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * enforces the provided value.
     *
     * @param $value
     * @return mixed
     */
    protected function enforceValue($value)
    {
        if ( $value instanceof \Closure ) {
            return $this->enforceCallable($value);
        }

        return $value;
    }

    /**
     * enforces the provided callback.
     *
     * @param callable $callback
     * @return mixed
     */
    protected function enforceCallable(callable $callback)
    {
        return call_user_func($callback);
    }
}
