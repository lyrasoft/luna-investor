<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $view      \Lyrasoft\Investor\Module\Admin\DividendHistory\\Lyrasoft\Investor\Module\Admin\DividendHistory\\Lyrasoft\Investor\Module\Admin\DividendHistory\\Lyrasoft\Investor\Module\Admin\DividendHistory\\Lyrasoft\Investor\Module\Admin\DividendHistory\\Lyrasoft\Investor\Module\Admin\DividendHistory\DividendHistoryEditView  The view modal object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\Investor\Module\Admin\DividendHistory\DividendHistoryEditView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;

use Windwalker\Core\Router\
<div x-title="toolbar" x-data="{ form: $store.form }" class="l-toolbar">
    {{-- Save --}}
<button type="button" class="btn btn-success btn-sm uni-btn-save"
    data-task="save"
    @click="form.post();"
    style="width: 150px"
>
    <span class="fa fa-save"></span>
    @lang('unicorn.toolbar.save')
</button>

{{-- Save2Close --}}
<button type="button" class="btn btn-primary btn-sm uni-btn-save2close"
    data-task="save"
    @click="form.post(null, { task: 'save2close' });">
    <span class="fa fa-check"></span>
    @lang('unicorn.toolbar.save2close')
</button>

{{-- Cancel --}}
<a class="btn btn-default btn-outline-secondary btn-sm uni-btn-cancel"
    href="{{ $nav->to('dividend_history_list') }}">
    <span class="fa fa-times"></span>
    @lang('unicorn.toolbar.cancel')
</a>
</div>
