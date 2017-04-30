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


use Phulsar\Application;

interface ApplicationKernelInterface
{
    /**
     * boots the kernel under the circumstances of the actual application container.
     *
     * @param Application $application
     * @return mixed
     */
    public function boot(Application $application);

    /**
     * runs the kernel under the circumstances of the actual application container.
     *
     * @param Application $application
     * @return mixed
     */
    public function run(Application $application);
}