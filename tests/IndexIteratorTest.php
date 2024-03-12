<?php

declare(strict_types=1);

namespace Dsentker\Tests;

use DSentker\IndexIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IndexIteratorTest extends TestCase
{
    #[DataProvider('toNormalizedArrayProvider')]
    public function testToNormalizedArray(array $input, array $expectedIndexes)
    {
        $instance = new IndexIterator();
        $instance->append(new \ArrayIterator($input));
        $this->assertEquals($expectedIndexes, $instance->toNormalizedArray());
    }

    public static function toNormalizedArrayProvider(): array
    {
        return [
            [[], []],
            [[100, 10, 0, 10], [0, 10, 100]],
            [[21, 2, 3, 4, 11, 6, 7, 11, 20, 19, 18, 0, 17], [0, 2, 3, 4, 6, 7, 11, 17, 18, 19, 20, 21]],
        ];
    }
}
