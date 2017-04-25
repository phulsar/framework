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


interface ServiceMethodInterface
{
    /**
     * sets the value for a given parameter.
     *
     * @param string $parameter
     * @param null $value
     * @return ServiceMethodInterface
     */
    public function withParameter(string $parameter, $value = null): ServiceMethodInterface;

    /**
     * ensures that the provided optional parameters are fulfilled.
     *
     * @param \string[] ...$parameters
     * @return ServiceMethodInterface
     */
    public function withOptionalParameters(string ... $parameters): ServiceMethodInterface;

    /**
     * creates a parameter object for a variadic parameter usable as a parameter or argument value.
     *
     * @param array ...$values
     * @return VariadicParameterInterface
     */
    public static function variadic(... $values): VariadicParameterInterface;
}
