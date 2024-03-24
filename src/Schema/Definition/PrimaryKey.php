<?php declare(strict_types=1);

namespace QkSkima\Base\Schema\Definition;

class PrimaryKey
{

    public function __construct(
        private readonly string $columnName
    ) {}

    public function equals(string $columnName): bool
    {
        return $this->columnName === $columnName;
    }

    public function getPrimaryKeyColumn(): string
    {
        return $this->columnName;
    }
}