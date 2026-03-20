<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Data;

use Windwalker\Data\RecordInterface;
use Windwalker\Data\RecordTrait;

class Shareholder implements RecordInterface
{
    use RecordTrait;

    public function __construct(
        public string $name = '',
        public string $shares = '',
        public string $percentage = '',
    ) {
    }
}
