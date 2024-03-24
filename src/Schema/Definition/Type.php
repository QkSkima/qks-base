<?php declare(strict_types=1);

namespace QkSkima\Base\Schema\Definition;

use QkSkima\Base\Schema\Definition\Traits\Columns;
use QkSkima\Base\Schema\Definition\Traits\Fields;

class Type
{
    use Columns;
    use Fields;

    private array $columns = [];

    public function __construct(
        private readonly string $type
    ) {}

    public function equals(string $type): bool
    {
        return $this->type === $type;
    }
}