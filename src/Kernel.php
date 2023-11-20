<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

echo 'hello';
die;
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
