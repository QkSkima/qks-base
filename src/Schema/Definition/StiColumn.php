<?php declare(strict_types=1);

namespace QkSkima\Base\Schema\Definition;

class StiColumn
{

    public function __construct(
        private readonly string $columnName
    ) {}

    public function equals(string $columnName): bool
    {
        return $this->columnName === $columnName;
    }
}