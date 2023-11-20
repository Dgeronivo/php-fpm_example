<?php

declare(strict_types=1);

namespace App\DomainLayer\Service;

use App\DataLayer\CellStorage;
use App\DomainLayer\Entity\Cell;

class CellProvider
{
    public function __construct(
        private readonly CellStorage $cellStorage,
        private readonly CellCalculator $cellCalculator,
    ) {}

    public function getCell(string $sheetId, string $cellId): array
    {
        $cell = $this->cellStorage->find($sheetId, $cellId);
        if ($cell) {
            return $this->prepareResponse($cell);
        }

        return [];
    }

    public function getSheet(string $sheetId): array
    {
        $cells = $this->cellStorage->getAllForSheet($sheetId);
        $response = [];
        foreach ($cells as $cell) {
            $response[$cell->getId()] = $this->prepareResponse($cell);
        }

        return $response;
    }

    private function prepareResponse(Cell $cell): array
    {
        $result = $this->cellCalculator->calculate($cell->getSheetId(), $cell->getId(), $cell->getValue());

        return [
            'value' => $cell->getValue(),
            'result' => $result,
        ];
    }
}
