<?php declare(strict_types=1);

namespace QkSkima\Base\Schema\Definition\Traits;

use QkSkima\Base\Exception;
use QkSkima\Base\Schema\Definition\Column;
use QkSkima\Base\Schema\Definition\Table;
use QkSkima\Base\Schema\Definition\Type;

trait Fields
{
    /**
     * Internal function to specify a tables column
     * @param string $field
     * @return void
     */
    public function hasField(string $field): bool
    {
        return in_array(true, array_map(function($el) use ($field) {
            if (get_class($el) === Column::class) {
                $equalByFieldName = $el->equals($field);
                $equalByColumnName = $el->getColumnName() === $field;
                return $equalByFieldName || $equalByColumnName;
            }
            return $el->hasField($field);
        }, (get_class($this) === Table::class && count($this->types) > 0) ? $this->types : $this->columns));
    }

    public function findColumn(string $field): Column
    {
        $columns = array_filter(
            array_map(function($el) use ($field) {
                if (get_class($el) === Column::class) {
                    $equalByFieldName = $el->equals($field);
                    $equalByColumnName = $el->getColumnName() === $field;
                    return $equalByFieldName || $equalByColumnName ? $el : false;
                }
                return $el->findColumn($field);
            }, (get_class($this) === Table::class && count($this->types) > 0) ? $this->types : $this->columns)
        );

        if (count($columns) > 0) {
            return $columns[0];
        }
    }
}