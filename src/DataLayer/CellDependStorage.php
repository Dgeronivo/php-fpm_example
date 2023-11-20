<?php

declare(strict_types=1);

namespace App\DataLayer;


use App\DomainLayer\Entity\Cell;
use App\DomainLayer\Entity\CellDepend;

class CellDependStorage
{
    private const PREFIX = 'depend_';

    public function addDepend(CellDepend $cellDepend): void
    {

    }

    public function removeDepends(Cell $cell): void
    {

    }

    public function getDepends(Cell $cell): void
    {

    }

    private function getRedis(): Redis
    {
        return Redis::getInstance();
    }
}
