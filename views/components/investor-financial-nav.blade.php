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

$attributes = $attributes->class('card w-100 mx-auto');
$attributes['style'] = 'max-width: 450px;';

$menu = $app->retrieve(MenuService::class)->createBuilder('investor-front-nav');

$menu->link(
    '每月營收報告',
    $nav->to('investor_monthly_report'),
);

$menu->link(
    '每季財務報告',
    $nav->to('investor_season_report'),
);

$menu->link(
    '公司年報',
    $nav->to('investor_annual_report'),
);

// $menu->link(
//     '公司年報',
//     $nav->to('investor_dividend_history'),
// );

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

