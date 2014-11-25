# Genkgo.Migrations

Library to make migrations easy. If you are looking for a tool (not a library) that has database migrations integrated
and comes with a command line interface, please have a look at [robmorgan/phinx](https://github.com/robmorgan/phinx).
If you need a library without these tools, but can work with any database abstraction layer or other dependencies, this
library might be the correct choice.

### Installation

Requires PHP 5.5 or later. There are no plans to support PHP 5.4 or PHP 5.3. In case this is an obstacle for you,
conversion should be no problem. The library is very small.

It is installable and autoloadable via Composer as [genkgo/migrations](https://packagist.org/packages/genkgo/migrations).

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/genkgo/migrations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/genkgo/migrations/)
[![Code Coverage](https://scrutinizer-ci.com/g/genkgo/migrations/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/auraphp/Aura.Di/)

To run the unit tests at the command line, issue `phpunit -c tests/`. [PHPUnit](http://phpunit.de/manual/) is required.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Getting Started

### Create a migration file

Create a migration file, define how to update and undo the changes in your application.

```php
<?php
use Genkgo\Migrations\AbstractMigration;

class migration_2014_11_13_11_55 extends AbstractMigration
{

    public function up()
    {
        // your changes here
    }

    public function down()
    {
        // undo changes here
    }
}
```

### Migrate

The adapter keeps the history of the already executed migrations. The current library comes with a MySQL and Sqlite
adapter. To add a new adapter, create one yourself and issue a pull request. Set the adapter, create a new list (from a
list of files in a directory) and execute.

```php
<?php
use PDO;
use Genkgo\Migrations\Factory;
use Genkgo\Migrations\Adapters\PdoSqliteAdapter;

$adapter = new PdoSqliteAdapter(new PDO('sqlite::memory:'));
$factory = new Factory($adapter);
$directory = __DIR__.'/migrations';
$list = $this->factory->newListFromDirectory($directory);
$result = $list->migrate();
```

### Inject dependencies
You can inject your dependencies, e.g. some database abstraction layer like in the sample below.

```php
<?php
use PDO;
use Genkgo\Migrations\Factory;
use Genkgo\Migrations\Adapters\PdoSqliteAdapter;
use Vendor\DatabaseAbstractLayer\SchemaGenerator;

$adapter = new PdoSqliteAdapter(new PDO('sqlite::memory:'));
$factory = new Factory($adapter, function ($classname) {
    return new $classname(new SchemaGenerator());
});
```

And then use it the migration file.

```php
<?php
use Genkgo\Migrations\AbstractMigration;
use Vendor\DatabaseAbstractLayer\SchemaGenerator;

class migration_2014_11_13_11_55 extends AbstractMigration
{
    private $schema;

    public function __construct(SchemaGenerator $schema) {
        $this->schema = $schema;
    }

    public function up()
    {
        // your changes here
    }

    public function down()
    {
        // undo changes here
    }
}
```

The example below uses [auto resolution of Aura.Di](https://github.com/auraphp/Aura.Di#auto-resolution-of-parameter-values).

```php
<?php
use PDO;
use Genkgo\Migrations\Factory;
use Genkgo\Migrations\Adapters\PdoSqliteAdapter;

$adapter = new PdoSqliteAdapter(new PDO('sqlite::memory:'));
$factory = new Factory($adapter, function ($classname) use ($di) {
    return $di->newInstance($classname);
});
```

### Multiple lists

You can have multiple migration lists, e.g. for each plugin one list. Just put the migration files in the right
namespace. See example below.

```php
<?php
namespace Vendor\MyPlugin;

use Genkgo\Migrations\AbstractMigration;

class migration_2014_11_13_11_55 extends AbstractMigration
{

    public function up () { }
    public function down () { }

}
```

And then migrate.

```php
$directory = __DIR__. '/migrations';
$list = $this->factory->newListFromDirectory($directory,'Vendor\\Plugin\\');
$list->migrate();
```


## Roadmap

- Add support for rolling back to a certain point in time
- Optimize the PDO adapter, too many queries now (one per migration). Should be one per namespace.
- ... no further plans yet

## Contributing

- Found a bug? Please try to solve it yourself first and issue a pull request. If you are not able to fix it, at least
  give a clear description what goes wrong. We will have a look when there is time.
- Want to see a feature added, issue a pull request and see what happens. You could also file a bug of the missing
  feature and we can discuss how to implement it.
