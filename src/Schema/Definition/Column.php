<?php declare(strict_types=1);

namespace QkSkima\Base\Schema\Definition;

class Column
{
    //string $tablename, string $field, string $column, string $field_type, string $type = null, string $foreignTable = null, string $foreignKey = null
    public function __construct(
        private string $field,
        private string $column,
        private string $fieldtype
    ) {}

    public function equals(string $fieldName): bool
    {
        return $this->field === $fieldName;
    }

    public function getFieldName(): string
    {
        return $this->field;
    }

    public function getColumnName(): string
    {
        return $this->column;
    }

    public function getFieldtype(): string
    {
        return $this->fieldtype;
    }
}