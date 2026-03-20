<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Data;

use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;

class MonthlyReport implements RecordInterface
{
    use RecordTrait;

    public function __construct(
        public string $revenue = '',
        public string $yoy = '',
        public string $cumulativeRevenue = '',
        public string $cumulativeYoy = '',
        public string $revenueSummary = '',
    ) {
    }
}
