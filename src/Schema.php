<?php declare(strict_types=1);

#
# MIT License
#
# Copyright (c) 2024 Colin Atkins
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.
#

namespace QkSkima\Base;

use QkSkima\Base\Schema\Definition\Column;
use QkSkima\Base\Schema\Definition\StiColumn;
use QkSkima\Base\Schema\Definition\Table;
use QkSkima\Base\Schema\Exceptions\TableNotFound;


final class Schema
{
    private array $tables = [];

    /**
     * Main method for schema definition within QkSkima
     * @param \Closure $closure
     * @return Schema
     */
    public static function define(\Closure $closure): Schema
    {
        $instance = new self();
        $cl = $closure->bindTo($instance, $instance);
        $cl->call($instance, $instance);
        return $instance;
    }

    /**
     * Internal function to add a table to the schema
     * @param string $tablename
     * @param \Closure $block
     * @return void
     * @throws \Exception
     */
    private function table(string $tablename, \Closure $closure): void
    {
        if ($this->hasTable($tablename))
            throw new \Exception("Table $tablename definition already exists");

        $table = new Table($tablename);
        $this->tables[] = $table;
        $closure($table);
    }

    public function hasTable(string $tablename): bool
    {
        /**
         * @var Table $table
         */
        foreach ($this->tables as $table) {
            if ($table->equals($tablename)) {
                return true;
            }
        }
        return false;
    }

    public function findTable(string $tablename): Table
    {
        /**
         * @var Table $table
         */
        foreach ($this->tables as $table) {
            if ($table->equals($tablename)) {
                return $table;
            }
        }

        throw new TableNotFound("Table $tablename not found");
    }

    public function hasField(string $tablename, string $fieldName): bool
    {
        if ($this->hasTable($tablename)) {
            $table = $this->findTable($tablename);
            return $table->hasField($fieldName);
        }

        return false;
    }

    public function findColumn(string $tablename, string $fieldNameOrColumn): Column
    {
        if ($this->hasField($tablename, $fieldNameOrColumn)) {
            $table = $this->findTable($tablename);
            return $table->findColumn($fieldNameOrColumn);
        }

        throw new \QkSkima\Base\Schema\Exceptions\FieldNotFound("Table $tablename has not field or column with the name $fieldNameOrColumn");
    }

    public function findPrimaryKey(string $tablename): \QkSkima\Base\Schema\Definition\PrimaryKey
    {
        return $this->findTable($tablename)->primaryKey();
    }

    public function findStiColumn(string $tablename): StiColumn
    {
        return $this->findTable($tablename)->sti();
    }
}