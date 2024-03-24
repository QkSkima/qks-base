<?php declare(strict_types=1);

namespace QkSkima\Base;

use QkSkima\Base\Mapper\FieldNormalizer;
use QkSkima\Base\Mapper\PromiseInitializer;
use QkSkima\Base\Mapper\Promises\AbstractPromise;

/**
 * Only task of the mapper is to return a promise for a virtual field or an actual column according to the schema.
 */
final class Mapper
{

    private ?FieldNormalizer $fieldNormalizer;
    private ?PromiseInitializer $promiseInitializer;

    public function __construct(
        private readonly string $tablename,
        private readonly Schema $schema
    ) {
        $this->fieldNormalizer = new FieldNormalizer($this->tablename, $this->schema);
        $this->promiseInitializer = new PromiseInitializer($this->tablename, $this->schema);
    }

    public function fieldPromiseFor(string $fieldOrColumnName): AbstractPromise
    {
        $fieldName = $this->fieldNormalizer->normalizeField($fieldOrColumnName);
        return $this->promiseInitializer->promise($fieldName);
    }
}