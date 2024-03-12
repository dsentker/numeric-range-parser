<?php

declare(strict_types=1);

namespace DSentker;

abstract class NumericRangeParser
{
    public function __construct(
        protected readonly string $rangeSeparator = '-',
        protected readonly string $blockSeparator = ';',
    ) {
    }

    public function parse(string $input): IndexIterator
    {
        $input = $this->normalizeRangeInput($input);
        $iterator = new IndexIterator();
        $blocks = explode($this->blockSeparator, $input);
        foreach ($blocks as $block) {
            $indexes = $this->parseBlock(trim($block));
            $iterator->append($indexes);
        }

        return $iterator;

    }

    /**
     * @throws RangeException
     */
    protected function parseBlock(string $block): \ArrayIterator
    {
        if ('' === $block) {
            return new \ArrayIterator($this->createRangeFromEmptyBlock());
        }

        $blockRange = array_filter(explode($this->rangeSeparator, $block), function (string $value) {
            $value = trim($value);
            if ('' === $value) {
                return false;
            }

            return true;
        });

        /**
         * Do not allow negative numbers
         * @var list<int> $blockRange
         */
        $blockRange = array_map('abs', array_map('intval', $blockRange));
        $blockRangeCount = count($blockRange);

        if (0 === $blockRangeCount) {
            $range = [];
        } elseif(1 === $blockRangeCount) {
            $range = [array_pop($blockRange)];
        } elseif(2 === $blockRangeCount) {
            [$start, $end] = $blockRange;
            $range = $this->createRangeFromValues($start, $end);
        } else {
            throw RangeException::tooManyIndexSeparators($block);
        }

        return new \ArrayIterator($range);
    }

    /**
     * @return list<int>
     */
    abstract protected function createRangeFromValues(int $start, int $end): array;

    abstract protected function createRangeFromEmptyBlock(): array;

    protected function normalizeRangeInput(string $input): string
    {
        return trim($input);
    }
}
