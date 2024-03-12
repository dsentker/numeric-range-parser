<?php

declare(strict_types=1);

namespace DSentker;

final class DefaultNumericRangeParser extends NumericRangeParser
{
    protected function createRangeFromValues(int $start, int $end): array
    {
        if ($start > $end) {
            [$start, $end] = [$end, $start];
        }

        return range($start, $end);
    }

    protected function createRangeFromEmptyBlock(): array
    {
        return [];
    }


}
