<?php
/**
 * This file is part of the PHulsar Framework.
 *
 * (c)2017 Matthias Kaschubowski
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Phulsar\Services\Service;


use Phulsar\Contract\VariadicParameterInterface;

/**
 * Class VariadicParameter
 * @package Phulsar\Services\Service
 */
class VariadicParameter implements VariadicParameterInterface
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * VariadicParameter constructor.
     * @param array ...$values
     */
    public function __construct(... $values)
    {
        $this->values = $values;
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Generator
     */
    public function getIterator()
    {
        yield from $this->values;
    }

}
