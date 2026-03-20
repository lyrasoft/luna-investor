<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $vm        object          The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyrasoft\Luna\Services\MenuService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Edge\Component\ComponentAttributes;

/**
 * @var $attributes ComponentAttributes
 */

$attributes = $attributes->class('card');

$menu = $app->retrieve(MenuService::class)->createBuilder('investor-financial-nav');

$menu->link(
    $lang('investor.nav.item.monthly.report'),
    $nav->to('investor_monthly_report'),
);

$menu->link(
    $lang('investor.nav.item.season.report'),
    $nav->to('investor_season_report'),
);

$menu->link(
    $lang('investor.nav.item.annual.report'),
    $nav->to('investor_annual_report'),
);

$menu->link(
    $lang('investor.nav.item.dividend.history'),
    $nav->to('investor_dividend_history'),
);

$menu->link(
    $lang('investor.nav.item.shareholder.tops'),
    $nav->to('investor_shareholder_tops'),
);

$menu->link(
    $lang('investor.nav.item.shareholder.meetings'),
    $nav->to('investor_shareholder_meetings'),
);

?>
<div {!! $attributes !!}>
    <div class="card-body p-2">
        <div class="nav nav-pills nav-fill">
            @foreach ($menu->items() as $menuItem)
                <div class="nav-item">
                    <a class="nav-link {{ $menuItem->isActive() ? 'active' : '' }}"
                        href="{{ $menuItem->getLink() }}">
                        {{ $menuItem->getTitle() }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

