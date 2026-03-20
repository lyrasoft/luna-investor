<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Routes;

use Lyrasoft\Investor\Module\Front\DividendHistory\DividendHistoryView;
use Lyrasoft\Investor\Module\Front\FinancialReport\FinancialReportView;
use Lyrasoft\Investor\Module\Front\MonthlyReport\MonthlyReportView;
use Lyrasoft\Investor\Module\Front\Shareholder\ShareholderView;
use Windwalker\Core\Router\RouteCreator;

/** @var  RouteCreator $router */

$router->group('investor')
    ->register(function (RouteCreator $router) {
        $router->any('investor_dividend_history', '/investor/dividend')
            ->view(DividendHistoryView::class)
            ->extra('limit', 5);
    });
