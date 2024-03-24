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

namespace QkSkima\Base\Mapper;

use QkSkima\Base\Mapper\Promises\AbstractPromise;
use QkSkima\Base\Mapper\Promises\BelongsTo;
use QkSkima\Base\Mapper\Promises\Boolean;
use QkSkima\Base\Mapper\Promises\Date;
use QkSkima\Base\Mapper\Promises\Datetime;
use QkSkima\Base\Mapper\Promises\Floating;
use QkSkima\Base\Mapper\Promises\HasMany;
use QkSkima\Base\Mapper\Promises\HasOne;
use QkSkima\Base\Mapper\Promises\InheritanceColumn;
use QkSkima\Base\Mapper\Promises\Integer;
use QkSkima\Base\Mapper\Promises\PrimaryKey;
use QkSkima\Base\Mapper\Promises\Text;
use QkSkima\Base\Mapper\Promises\Time;
use QkSkima\Base\Mapper\Promises\Timestamp;
use QkSkima\Base\Mapper\Promises\Varchar;
use QkSkima\Base\Schema;

class PromiseInitializer
{
    public function __construct(
        private readonly string $tablename,
        private readonly Schema $schema
    ) {}

    public function promise(string $fieldName): AbstractPromise
    {
        $column = $this->schema->findColumn($this->tablename, $fieldName);
        $fieldtype = $column->getFieldtype();
        return match ($fieldtype) {
            FieldTypes::VARCHAR => new Varchar($column),
            FieldTypes::TEXT => new Text($column),
            FieldTypes::INTEGER => new Integer($column),
            FieldTypes::FLOAT => new Floating($column),
            FieldTypes::DATETIME => new Datetime($column),
            FieldTypes::DATE => new Date($column),
            FieldTypes::TIME => new Time($column),
            FieldTypes::TIMESTAMP => new Timestamp($column),
            FieldTypes::BOOLEAN => new Boolean($column),
            FieldTypes::PRIMARY_KEY => new PrimaryKey($column),
            FieldTypes::INHERITANCE_COLUMN => new InheritanceColumn($column),
            FieldTypes::BELONGS_TO => new BelongsTo($column),
            FieldTypes::HAS_MANY => new HasMany($column),
            FieldTypes::HAS_ONE => new HasOne($column),
            default => throw new Schema\Exceptions\FieldtypeUnknown("Fieldtype $fieldtype was unknown")
        };
    }
}