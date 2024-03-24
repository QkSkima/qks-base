<?php declare(strict_types=1);

namespace QkSkima\Base\Schema\Definition;

use QkSkima\Base\Exception;
use QkSkima\Base\Schema\Definition\Traits\Columns;
use QkSkima\Base\Schema\Definition\Traits\Fields;

class Table
{
    use Columns;
    use Fields;

    private array $types = [];

    private array $columns = [];

    private ?StiColumn $stiColumn = null;

    private ?PrimaryKey $primaryKey = null;

    public function __construct(
        private readonly string $tablename
    ) {}

    public function equals(string $tablename): bool
    {
        return $this->tablename === $tablename;
    }

    public function addType(string $typeName): Type
    {
        $type = new Type($typeName);
        $this->types[] = $type;
        return $type;
    }

    public function findType(string $typeName): Type
    {
        /**
         * @var Type $type
         */
        foreach ($this->types as $type) {
            if ($type->equals($typeName)) {
                return $type;
            }
        }

        throw new Exception("Type $typeName not found");
    }

    private function hasType(string $typeName): bool
    {
        /**
         * @var Type $type
         */
        foreach ($this->types as $type) {
            if ($type->equals($typeName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Internal function to specify a type column for single-table inheritance
     * @param string $typeName
     * @param \Closure $block
     * @return void
     * @throws \Exception
     */
    public function type(string $typeName, \Closure $closure): void
    {
        if (!$this->hasType($typeName)) {
            $type = $this->addType($typeName);
        } else {
            $type = $this->findType($typeName);
        }
        $closure($type);
    }

    /**
     * Internal function to set a tables single-table inheritance column
     * @param string $stiColumn
     * @return void
     */
    public function stiColumn(string $stiColumn): void
    {
        if (!is_null($this->stiColumn)) {
            throw new Exception("STI column already set for table $this->tablename");
        }
        $this->stiColumn = new StiColumn($stiColumn);
    }

    /**
     * Internal function to set a tables primary key
     * @param string $primaryKeyColumn
     * @return void
     */
    public function primaryKeyColumn(string $primaryKeyColumn): void
    {
        if (!is_null($this->primaryKey)) {
            throw new Exception("Primary key already set for table $this->tablename");
        }
        $this->primaryKey = new PrimaryKey($primaryKeyColumn);
    }

    public function primaryKey(): PrimaryKey
    {
        return
            is_null($this->primaryKey) ?
                throw new Exception("Primary key is null for table $this->tablename") :
                $this->primaryKey;
    }

    public function sti(): StiColumn
    {
        return
            is_null($this->stiColumn) ?
                throw new Exception("Single-table-inheritance column is null for table $this->tablename") :
                $this->stiColumn;
    }
}