<?php

declare(strict_types=1);

namespace App\DomainLayer\Service;

use App\DataLayer\CellStorage;
use App\DomainLayer\Entity\Cell;

class CellUpdater
{
    public function __construct(
        private readonly CellCalculator $calculator,
        private readonly CellStorage $cellStorage,
    ) {}

    public function removeCell(string $sheetId, string $cellId): void
    {
        $this->cellStorage->remove($sheetId, $cellId);
    }

    public function update(string $sheetId, string $cellId, string $formula): array
    {
        try {
            $result = $this->calculator->calculate($sheetId, $cellId, $formula);
        } catch (\Exception $e) {
            return $this->prepareResponse($formula, $e->getMessage(), true);
        }

        // todo trigger depended

        $cell = new Cell(
            $cellId,
            $sheetId,
            $formula,
            $result,
        );

        $this->cellStorage->save($cell);

        return $this->prepareResponse($cell->getValue(), $cell->getResult(), false);
    }

    private function prepareResponse(string $value, string $result, bool $withError): array
    {
        return [
            'value' => $value,
            'result' => $result,
            'withError' => $withError,
        ];
    }
}
