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

final class SchemaTest extends TestCase
{
    private \QkSkima\Base\Schema $schemaWithType;

    public function setUp(): void
    {
        $this->schemaWithType = \QkSkima\Base\Schema::define(function ($schema) {
            $schema->table(tablename: 'tt_content', closure: function ($table) {
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
    }

    public function testTableDefinition(): void
    {
        $this->assertTrue($this->schemaWithType->hasTable('tt_content'));
        $this->assertFalse($this->schemaWithType->hasTable('pages'));
    }

    public function testHasFields(): void
    {
        $this->assertTrue($this->schemaWithType->hasField('tt_content', 'headline'));
        $this->assertTrue($this->schemaWithType->hasField('tt_content', 'intro'));
        $this->assertTrue($this->schemaWithType->hasField('tt_content', 'count'));
    }

    public function testHasntField(): void
    {
        $this->assertFalse($this->schemaWithType->hasField('tt_content', 'not-existing'));
    }

    public function testHasPrimaryKeyColumn(): void
    {
        $this->assertTrue(
            $this->schemaWithType->findPrimaryKey('tt_content')->equals('uid')
        );
    }

    public function testHasntPrimaryKeyColumn(): void
    {
        $this->assertFalse(
            $this->schemaWithType->findPrimaryKey('tt_content')->equals('id-somewhat')
        );
    }

    public function testHasStiColumn(): void
    {
        $this->assertTrue(
            $this->schemaWithType->findStiColumn('tt_content')->equals('CType')
        );
    }

    public function testHasntStiColumn(): void
    {
        $this->assertFalse(
            $this->schemaWithType->findStiColumn('tt_content')->equals('Some-type-col')
        );
    }

    public function testFindColumn(): void
    {
        $column = $this->schemaWithType->findColumn('tt_content', 'headline');
        $this->assertEquals(
            'headline',
            $column->getFieldName()
        );
    }

    public function testFieldNotFound(): void
    {
        $this->expectException(\QkSkima\Base\Schema\Exceptions\FieldNotFound::class);
        $this->schemaWithType->findColumn('tt_content', 'field-not-present');
    }
}


