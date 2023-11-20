<?php

declare(strict_types=1);

namespace App\DomainLayer\Entity;

class Cell
{
    public function __construct(
        private readonly string $id,
        private readonly string $sheetId,
        private readonly string $value,  // todo think about cache and search relates variable
        private readonly string $result,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getSheetId(): string
    {
        return $this->sheetId;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getResult(): string
    {
        return $this->result;
    }
}
