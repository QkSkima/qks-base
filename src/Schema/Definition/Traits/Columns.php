<?php declare(strict_types=1);

namespace QkSkima\Base\Schema\Definition\Traits;

use QkSkima\Base\Schema\Definition\Column;
use QkSkima\Base\Schema\Definition\Table;

trait Columns
{
    /**
     * Internal function to specify a tables column
     * @param string $field
     * @param string $columnName
     * @param string $fieldtype
     * @return void
     */
    public function column(string $field, string $columnName, string $fieldtype): void
    {
        /**
         * @var Column $column
         */
        foreach ($this->columns as $column) {
            if ($column->equals($field)) {
                return;
            }
        }

        $this->columns[] = new Column($field, $columnName, $fieldtype);
    }

    public function hasColumn(string $column): bool
    {
        return in_array(true, array_map(function($el) use ($column) {
            if (get_class($el) === Column::class) {
                return $el->getColumnName() === $column;
            }
            return $el->hasColumn($column);
        }, (get_class($this) === Table::class && count($this->types) > 0) ? $this->types : $this->columns));
    }
}