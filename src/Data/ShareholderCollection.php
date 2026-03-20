<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Data;

use Windwalker\Data\Collection;

class ShareholderCollection extends Collection
{
    public function set(mixed $key, mixed $value): static
    {
        $value = Shareholder::wrap($value);

        return parent::set($key, $value);
    }
}
