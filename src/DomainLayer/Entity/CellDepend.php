<?php

declare(strict_types=1);

namespace App\DomainLayer\Entity;

class CellDepend
{
    private string $dependentCellId;
    private string $targetCell;

    public function __construct(
        string $sheetId,
        string $dependentCellId,
        string $targetCell,
    ) {
        $this->dependentCellId = $sheetId.$dependentCellId;
        $this->targetCell = $targetCell;
    }

    public function getDependentCellId(): string
    {
        return $this->dependentCellId;
    }

    public function getTargetCell(): string
    {
        return $this->targetCell;
    }
}
