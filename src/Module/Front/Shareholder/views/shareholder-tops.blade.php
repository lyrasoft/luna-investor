<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        ShareholderView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\Investor\Module\Front\Shareholder\ShareholderView;
use Lyrasoft\Investor\Data\Shareholder;
use Lyrasoft\Investor\Entity\ShareholderInfo;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $item ShareholderInfo
 */
?>

@push('macro')
    <style data-macro type="text/scss" data-scope=".view-shareholder">
    </style>

    <script data-macro="shareholder" lang="ts" type="module">
</script>
@endpush

@extends('global.body')

@section('content')
    <div class="l-shareholder-page container py-5 d-flex flex-column gap-4">
        <x-investor-financial-nav class="mb-5" />

        <h2 class="text-center mb-4">
            @lang('investor.shareholder.tops.title')
        </h2>

        <section>
            <header>
                <h3>
                    @lang('investor.shareholder.head.tops')
                </h3>
            </header>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>
                        @lang('investor.shareholder.tops.field.name')
                    </th>
                    <th class="text-end">
                        @lang('investor.shareholder.tops.field.shares')
                    </th>
                    <th class="text-end">
                        @lang('investor.shareholder.tops.field.percentage')
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach($item->topShareholders->splice(0, 10) as $shareholder)
                    <tr>
                        <td>{{ $shareholder->name }}</td>
                        <td class="text-end">{{ $shareholder->shares }}</td>
                        <td class="text-end">{{ $shareholder->percentage }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>

        @if ($item->showPrimary)
            <section>
                <header>
                    <h3>
                        @lang('investor.shareholder.head.primary')
                    </h3>
                </header>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>
                            @lang('investor.shareholder.tops.field.name')
                        </th>
                        <th class="text-end">
                            @lang('investor.shareholder.tops.field.shares')
                        </th>
                        <th class="text-end">
                            @lang('investor.shareholder.tops.field.percentage')
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($item->primaryShareholders as $shareholder)
                        <tr>
                            <td>{{ $shareholder->name }}</td>
                            <td class="text-end">{{ $shareholder->shares }}</td>
                            <td class="text-end">{{ $shareholder->percentage }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    </div>
@stop
