<?php

declare(strict_types=1);

namespace App\DataLayer;

use App\DomainLayer\Entity\Cell;

class CellStorage
{
    private const FORMULA_FIELD = 'formula';
    private const RESULT_FIELD = 'result';

    public function save(Cell $cell): void
    {
        $value = [
            self::FORMULA_FIELD => $cell->getValue(),
            self::RESULT_FIELD => $cell->getResult(),
        ];

        $this->getRedis()->hset($cell->getSheetId(), $cell->getId(), json_encode($value, JSON_THROW_ON_ERROR));
    }

    public function find(string $sheetId, string $cellId): ?Cell
    {
        $value = $this->getRedis()->hget($sheetId, $cellId);
        if (is_null($value)) {
            return null;
        }

        return $this->restoreCell($sheetId, $cellId, $value);
    }

    /** @return Cell[] */
    public function getAllForSheet(string $sheetId): array
    {
        $data = $this->getRedis()->hgetall($sheetId);
        $cells = [];
        foreach ($data as $cellId => $value) {
            $cells[] = $this->restoreCell($sheetId, $cellId, $value);
        }

        return $cells;
    }

    public function remove(string $sheetId, string $cellId): void
    {
        $this->getRedis()->hdel($sheetId, $cellId);
    }

    private function restoreCell(string $sheetId, string $cellId, string $value)
    {
        $data = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

        return new Cell($cellId, $sheetId, $data[self::FORMULA_FIELD], $data[self::RESULT_FIELD]);
    }

    private function getRedis(): Redis
    {
        return Redis::getInstance();
    }
}
