<?php

declare(strict_types=1);

namespace DSentker;

class StrictNumericRangeParser extends NumericRangeParser
{
    protected function createRangeFromValues(int $start, int $end): array
    {
        if ($start > $end) {
            throw RangeException::invalidOrder($start, $end);
        }

        return range($start, $end);
    }

    protected function createRangeFromEmptyBlock(): array
    {
        throw RangeException::emptyBlock();
    }

    protected function normalizeRangeInput(string $input): string
    {
        $input = parent::normalizeRangeInput($input);

        if (mb_strlen($input) > 0) {
            $pattern = sprintf("/^[0-9%s%s]+\$/", $this->rangeSeparator, $this->blockSeparator);

            if (preg_match($pattern, $input) !== 1) {
                throw RangeException::invalidCharacters([$this->rangeSeparator, $this->blockSeparator]);
            }
        }

        return $input;
    }


}
