# NumericRangeParser

A PHP library that provides functionality to parse numeric ranges from a given input string. The library is suitable for
user input that wants to select specific page numbers or fetch a custom range of items.

## Installation

Install using [Composer](https://getcomposer.org/):

```bash
composer require dsentker/numeric-range-parser
```

## Usage

```php
$parser = new DefaultNumericRangeParser();
$result = $parser->parse('1-3;5');
dump($result->toNormalizedArray()); // [1, 2, 3, 5]


// $result is an iterator, iterating over indexes is possible to
foreach ($result as $index) {
    echo $index . PHP_EOL;
}

```

As `$result` is an instance of an [AppendIterator](https://www.php.net/manual/en/class.appenditerator.php) it is 
possible to append more indexes if required. Also, the `getIteratorIndex()` method returns the index of the current 
block (a block is separated by semicolon)

```php
$parser = new DefaultNumericRangeParser();
$result = $parser->parse('4-6; 10'); // two blocks defined here

$result->append(new \ArrayIterator([1-2])); // another block here

foreach ($result as $index) {
    // $result->getIteratorIndex() will count up to 2 (0,1,2)
    printf("Block #%d: Index: #%d", $result->getIteratorIndex(), $result->current());
}
```


## Configure options

`__construct(string $rangeSeparator = '-', string $blockSeparator = ';')`

The constructor allows you to set custom range and block separators. By default, the range separator is `'-'` and the block separator is `';'`.

```php
$instance = new \DSentker\DefaultNumericRangeParser('..', '/');
$result = $instance->parse('1..4 / 6..10');
```

## Strict parsing
While the `DefaultNumericRangeParser` is a little more lax on user input, the `StrictNumericRangeParser` parser is 
stricter. A `RangeException` is thrown if the input is incorrect:

```php
$default = new \DSentker\DefaultNumericRangeParser();
$strict = new \DSentker\StrictNumericRangeParser();

$default->parse('10-8'); // No error
$strict->parse('10-8'); // First index is greater than second, RangeException is thrown

$default->parse('8-10;;11'); // No error
$strict->parse('8-10;;11'); // Missing range, RangeException is thrown

$default->parse('8a;10;12'); // No error
$strict->parse('8a;10;12'); // Invalid character, RangeException is thrown
```

## Testing
with PHPUnit:
```bash
./vendor/bin/phpunit
```

## Submitting bugs and feature requests
Bugs and feature request are tracked on GitHub.

If you have general questions or want to implement a feature, you are welcome to collaborate.

