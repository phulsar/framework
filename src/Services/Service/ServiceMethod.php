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


use Phulsar\Contract\ServiceInterface;
use Phulsar\Contract\ServiceMethodInterface;
use Phulsar\Contract\VariadicParameterInterface;

class ServiceMethod implements ServiceMethodInterface
{
    protected $values = [];

    protected $optionalParameters = [];

    /**
     * sets the value for a given parameter.
     *
     * @param string $parameter
     * @param null $value
     * @return ServiceInterface|ServiceMethodInterface
     */
    public function withParameter(string $parameter, $value = null): ServiceMethodInterface
    {
        if ( func_num_args() === 1 ) {
            return $this->withOptionalParameters($parameter);
        }

        $this->values[$parameter] = $value;

        return $this;
    }

    /**
     * ensures that the provided optional parameters are fulfilled.
     *
     * @param \string[] ...$parameters
     * @return ServiceInterface|ServiceMethodInterface
     */
    public function withOptionalParameters(string ... $parameters): ServiceMethodInterface
    {
        $this->optionalParameters = array_merge(
            $this->optionalParameters,
            array_diff($this->optionalParameters, $parameters)
        );

        return $this;
    }

    /**
     * creates a parameter object for a variadic parameter usable as a parameter or argument value.
     *
     * @param array ...$values
     * @return VariadicParameterInterface
     */
    public static function variadic(... $values): VariadicParameterInterface
    {
        return new VariadicParameter(... $values);
    }

}
