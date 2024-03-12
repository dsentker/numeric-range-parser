<?php

declare(strict_types=1);

namespace DSentker;

class RangeException extends \RangeException
{
    private const INVALID_RANGE_ORDER = 1;
    private const TOO_MANY_INDEX_SEPARATORS = 2;
    private const EMPTY_BLOCK = 4;
    private const INVALID_CHARACTERS = 8;

    public static function invalidOrder(int $first, int $second): static
    {
        return new static(sprintf(
            'The index %d must be equal to or greater than %d!',
            $second,
            $first
        ), self::INVALID_RANGE_ORDER);
    }

    public static function tooManyIndexSeparators(string $input): static
    {
        return new static(sprintf(
            'The block "%s" has too many index separators!',
            $input
        ), self::TOO_MANY_INDEX_SEPARATORS);
    }

    public static function emptyBlock(): static
    {
        return new static('The block is empty and cannot be parsed!', self::EMPTY_BLOCK);
    }

    public static function invalidCharacters(array $allowedSeparators): static
    {
        return new static(
            sprintf('Invalid characters detected, only [0-9%s] is allowed.', implode('', $allowedSeparators)),
            self::INVALID_CHARACTERS
        );
    }
}
