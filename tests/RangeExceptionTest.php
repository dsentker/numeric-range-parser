<?php

declare(strict_types=1);

namespace Dsentker\Tests;

use DSentker\RangeException;
use PHPUnit\Framework\TestCase;

class RangeExceptionTest extends TestCase
{
    public function testInvalidCharacters(): void
    {
        $instance = RangeException::invalidCharacters(['a', 'b']);
        $this->assertEquals('Invalid characters detected, only [0-9ab] is allowed.', $instance->getMessage());
        $this->assertEquals(8, $instance->getCode());
    }

    public function testEmptyBlock(): void
    {
        $instance = RangeException::emptyBlock();
        $this->assertEquals('The block is empty and cannot be parsed!', $instance->getMessage());
        $this->assertEquals(4, $instance->getCode());
    }

    public function testTooManySeparators(): void
    {
        $instance = RangeException::tooManyIndexSeparators('x');
        $this->assertEquals('The block "x" has too many index separators!', $instance->getMessage());
        $this->assertEquals(2, $instance->getCode());
    }

    public function testInvalidOrder(): void
    {
        $instance = RangeException::invalidOrder(10, 15);
        $this->assertEquals('The index 15 must be equal to or greater than 10!', $instance->getMessage());
        $this->assertEquals(1, $instance->getCode());
    }
}
