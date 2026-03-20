<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Component;

use Closure;
use Windwalker\Core\Edge\Attribute\EdgeComponent;
use Windwalker\Edge\Component\AbstractComponent;

#[EdgeComponent('investor-financial-nav')]
class InvestorFinancialNavComponent extends AbstractComponent
{
    public function render(): Closure|string
    {
        return 'components.investor-financial-nav';
    }
}
