<?php

declare(strict_types=1);

namespace DSentker;

final class IndexIterator extends \AppendIterator
{
    /**
     * @return list<int>
     */
    public function toNormalizedArray(): array
    {
        $indexes = iterator_to_array($this, false);
        sort($indexes);
        return array_values(array_unique($indexes, \SORT_NUMERIC));
    }

}
