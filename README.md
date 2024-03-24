# QkSkima - Quickly (re-)assign database columns to any field definition

QkSkima is a drop-in solution to connect database schemas of on-premise CMS with web application frameworks.
The core library contains a DSL for writing up virtual field mappings. Fields are mappable to any database column
with the correct data type. It also supports single table inheritance (STI) types to load different content
types of one table.

QkSkima supports promises for database relations as well. The schema definition provided all information needed
to load related contents of the database.

QkSkima is designed to be completely independent of any querying language. It is combined with adapters which
are specific to databases and CMS like WordPress, Drupal or TYPO3. Thus, you can manage contents with a
headful on-premise CMS while outputting contents with an web application framework like Symfony or Laravel.

A short rundown of its features:

- Specify a schema for a table with a short DSL.

```php
\QkSkima\Base\Schema::define(function ($schema) {
    $schema->table(tablename: 'contents', closure: function ($table) {
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
    
        $table->primaryKeyColumn(primaryKeyColumn: 'id');
        $table->stiColumn(stiColumn: 'type');
    });
});
```

- Supports all the field types you need and even relations.

```php
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
```

- Schema registry for loading multiple schema definitions.

```php
$schema = \QkSkima\Base\Schema\Registry::getInstance()->find('contents');
$schema->hasTable('contents'); # => true
```

- Easy loading of field mappings for system independent database retrieval.

```php
$schema = \QkSkima\Base\Schema\Registry::getInstance()->find('contents');
$mapper = new \QkSkima\Base\Mapper(tablename: 'contents', schema: $schema);
$fieldPromise = $mapper->fieldPromiseFor('headline');
echo $fieldPromise->getColumnName(); # => qks_string_1
echo $fieldPromise->getFieldtype(); # => string
```

- Field to column normalizing and vice versa.

```php
$schema = \QkSkima\Base\Schema\Registry::getInstance()->find('contents');
$mapper = new \QkSkima\Base\Mapper(tablename: 'contents', schema: $schema);
$fieldPromiseA = $mapper->fieldPromiseFor(fieldOrColumnName: 'headline');
$fieldPromiseB = $mapper->fieldPromiseFor(fieldOrColumnName: 'qks_string_1');

echo $fieldPromiseA === $fieldPromiseB; # => true
```

## Principles of QkSkima

QkSkima doesn't write your database migrations. Instead, you don't need migrations anymore.
Just virtually assign any column to your field as long as the required datatype matches the data you want to save.
This is especially handy if you have many content elements all stored in the same table with a STI column.

Simply add a generic set of columns to your table and write up the schema definition for it.
A short example below.

```sql
CREATE TABLE contents (
  id INT NOT NULL,
  type VARCHAR(255) NOT NULL,
  qks_string_1 VARCHAR(255) NOT NULL,
  qks_string_2 VARCHAR(255) NOT NULL,
  qks_integer_1 INT NOT NULL,
  qks_integer_2 INT NOT NULL,
  qks_relation_1 INT NOT NULL,
  qks_relation_2 INT NOT NULL,

  PRIMARY KEY id
)
```

According to the principle mentioned above, your columns have generic names instead of specific ones.
This makes it easier to map the contents of the columns to any field you like at any given time without
worrying about migrations.

## License

QkSkima is Open Source and licensed under the MIT license: https://opensource.org/license/MIT

## Support

You can file bug reports here: 

## Copyright

Copyright 2024 - QkSkima by Colin Atkins