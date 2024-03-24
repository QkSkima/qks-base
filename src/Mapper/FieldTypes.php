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

final class FieldTypes
{
    public const VARCHAR = 'string';
    public const TEXT = 'text';
    public const INTEGER = 'integer';
    public const FLOAT = 'float';
    public const DECIMAL = 'decimal';
    public const DATETIME = 'datetime';
    public const TIMESTAMP = 'timestamp';
    public const TIME = 'time';
    public const DATE = 'date';
    public const BOOLEAN = 'boolean';
    public const BELONGS_TO = 'belongs_to';
    public const HAS_MANY = 'has_many';
    public const HAS_ONE = 'has_one';
    public const PRIMARY_KEY = 'primary_key';
    public const INHERITANCE_COLUMN = 'inheritance_column';
}