<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        FinancialReportView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\Investor\Module\Front\FinancialReport\FinancialReportView;
use Lyrasoft\Investor\Entity\FinancialStatement;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $item FinancialStatement
 */
?>

@push('macro')
    <style data-macro type="text/scss" data-scope=".view-financial-annual-report">
    </style>

    <script data-macro="financial.annual.report" lang="ts" type="module">
</script>
@endpush

@extends('global.body')

@section('content')
    <div class="l-finalcial-report-page l-finalcial-report-page--annual container py-5">
        <x-investor-financial-nav class="mb-5" />

        <section class="">
            <h2 class="text-center mb-4">
                @lang('investor.financial.annual.report.title')
            </h2>

            <div class="row row-cols-5 g-4">
                @foreach ($yearItems as $item)
                    <div class="col">
                        <div class="card p-4">
                            <div
                                class="card-body text-center d-flex flex-column justify-content-center align-items-center gap-2">
                                <i class="far fa-newspaper fa-2x"></i>
                                <h4 class="card-title m-0">
                                    <a href="{{ $item->annualReport }}" class="stretched-link link-dark" target="_blank">
                                        {{ $item->title }}
                                    </a>
                                </h4>
                                <div>
                                    @lang('investor.annual.report.button.download')
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <x-pagination :pagination="$pagination" />
        </section>
    </div>
@stop
