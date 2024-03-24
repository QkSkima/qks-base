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

use PHPUnit\Framework\TestCase;

final class MapperTest extends TestCase
{
    private ?\QkSkima\Base\Schema $schemaWithType = null;
    private ?\QkSkima\Base\Mapper $mapper = null;

    public function setUp(): void
    {
        $tablename = 'tt_content';
        $this->schemaWithType = \QkSkima\Base\Schema::define(function ($schema) use ($tablename) {
            $schema->table(tablename: $tablename, closure: function ($table) {
                $table->type(typeName: 'header-with-count', closure: function ($type) {
                    $type->column(
                        field: 'headline',
                        columnName: 'qks_string_1',
                        fieldtype: \QkSkima\Base\Mapper\FieldTypes::VARCHAR
                    );
                    $type->column(
                        field: 'intro',
                        columnName: 'qks_string_2',
                        fieldtype: \QkSkima\Base\Mapper\FieldTypes::VARCHAR
                    );
                    $type->column(
                        field: 'count',
                        columnName: 'qks_integer_1',
                        fieldtype: \QkSkima\Base\Mapper\FieldTypes::INTEGER
                    );
                });

                $table->primaryKeyColumn(primaryKeyColumn: 'uid');
                $table->stiColumn(stiColumn: 'CType');
            });
        });

        $this->mapper = new QkSkima\Base\Mapper($tablename, $this->schemaWithType);
    }

    public function testMapperForFieldPromise(): void
    {
        $promise = $this->mapper->fieldPromiseFor('headline');

        $this->assertEquals('headline', $promise->getFieldName());
        $this->assertEquals('qks_string_1', $promise->getColumnName());
        $this->assertEquals(\QkSkima\Base\Mapper\FieldTypes::VARCHAR, $promise->getFieldtype());
        $this->assertEquals(\QkSkima\Base\Mapper\Promises\Varchar::class, get_class($promise));
    }

    public function testFieldNormalization(): void
    {
        $mapper = new \QkSkima\Base\Mapper(tablename: 'tt_content', schema: $this->schemaWithType);
        $fieldPromiseA = $mapper->fieldPromiseFor(fieldOrColumnName: 'headline');
        $fieldPromiseB = $mapper->fieldPromiseFor(fieldOrColumnName: 'qks_string_1');

        $this->assertEquals(get_class($fieldPromiseB), get_class($fieldPromiseA));
        $this->assertEquals('headline', $fieldPromiseA->getFieldName());
        $this->assertEquals('headline', $fieldPromiseB->getFieldName());
        $this->assertEquals('qks_string_1', $fieldPromiseA->getColumnName());
        $this->assertEquals('qks_string_1', $fieldPromiseB->getColumnName());
    }
}