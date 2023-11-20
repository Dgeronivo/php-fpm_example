<?php

declare(strict_types=1);

namespace App\DomainLayer\Service;

use App\DataLayer\CellStorage;
use App\DomainLayer\Entity\Cell;

class CellCalculator
{
    private array $dependentCells = [];

    public function __construct(
        private readonly CellStorage $cellStorage,
    ) {}

    public function calculate(string $sheetId, string $cellId, string $expression): string
    {
        $value = $this->recursiveCalculate($sheetId, $cellId, $expression);
        $this->dependentCells = [];

        return $value;
    }

    private function recursiveCalculate(string $sheetId, string $cellId, string $expression): string
    {
        if (!str_starts_with($expression, '=')) {
            return $expression;
        }
        $expression = substr($expression, 1);

        if (!preg_match('/^[0-9+\-\/()*a-zA-Z_. ]+$/', $expression)) {
            throw new \Exception('Error: Invalid characters in formula');
        }

        $tokens = preg_split('/([+\-\/()*])/', $expression, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        foreach ($tokens as &$token) {
            if (preg_match('/\b[a-zA-Z_][a-zA-Z0-9_]*\b/', $token)) {
                $varName = trim($token);
                $cell = $this->getCell($sheetId, $varName);
                if (is_null($cell)) {
                    $token = '0'; // skip variable

                    continue;
                }

                $this->dependentCells[$cellId][] = $cell->getId();
                if ($this->isDependentFormula($cellId, $cell->getId())) {
                    throw new \Exception('Error: new value leads to dependent formula ERROR compilation');
                }

                $token = $this->recursiveCalculate($cell->getSheetId(), $cell->getId(), $cell->getValue());
            }
        }

        $expression = implode('', $tokens);

        try {
            eval('$result = ' . $expression . ';');
        } catch (\Throwable $e) {
            // Division on zero
            throw new \Exception("Error: {$e->getMessage()}");
        }

        return (string) $result;
    }

    private function isDependentFormula(string $parentCell, string $searchingCell): bool
    {
        return array_key_exists($searchingCell, $this->dependentCells)
            && in_array($parentCell, $this->dependentCells[$searchingCell], true);
    }

    private function getCell(string $sheetId, string $cellId): ?Cell
    {
        return $this->cellStorage->find($sheetId, $cellId);
    }
}
