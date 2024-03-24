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

namespace QkSkima\Base\Schema;

use QkSkima\Base\Exception;
use QkSkima\Base\Interfaces\Loader;
use QkSkima\Base\Mapper;
use QkSkima\Base\Schema;
use QkSkima\Base\Interfaces\Singleton;
use QkSkima\Base\Interfaces\Registry as RegistryInterface;

class Registry implements Singleton, RegistryInterface
{
    private static ?Registry $instance = null;

    private array $schemaLoaders = [];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance(): Registry
    {
        if (self::$instance === null)
            self::$instance = new self();

        return self::$instance;
    }

    public function addLoader(Loader $loader): void
    {
        $this->schemaLoaders[] = $loader;
    }

    public function find(string $tablename): Schema
    {
        /**
         * @var $loader Loader
         */
        foreach ($this->schemaLoaders as $loader) {
            try {
                $schema = $loader->load();
                $schema->findTable($tablename);
                return $schema;
            } catch (Schema\Exceptions\TableNotFound $e) {
            }
        }
        throw new Schema\Exceptions\SchemaNotFound("No schema was found for the table $tablename");
    }

    public function numberOfLoaders(): int
    {
        return count($this->schemaLoaders);
    }
}