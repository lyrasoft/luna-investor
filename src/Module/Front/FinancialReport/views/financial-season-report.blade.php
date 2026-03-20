<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var  $vm        FinancialReportView  The view model object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

use Lyrasoft\Investor\Module\Front\FinancialReport\FinancialReportView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

?>

@push('macro')
<style data-macro type="text/scss" data-scope=".view-financial-season-report">
</style>

<script data-macro="financial.season.report" lang="ts" type="module">
</script>
@endpush

@extends('global.body')

@section('content')
    <div class="l-finalcial-report-page l-finalcial-report-page--season container py-5">
        <x-investor-financial-nav class="mb-5" />

        <section class="">
            <h2 class="h1 mb-lg-40px mb-20px text-center">
                @lang('investor.financial.season.report.title')
            </h2>

            <div class="c-report-table">
                <div class="c-report-table__body table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">@lang('investor.text.year')</th>
                            <th class="text-center">@lang('investor.season.report.season1.merge.title')</th>
                            <th class="text-center">@lang('investor.season.report.season2.merge.title')</th>
                            <th class="text-center">@lang('investor.season.report.season3.merge.title')</th>
                            <th class="text-center">@lang('investor.season.report.season4.merge.title')</th>
                            <th class="text-center">@lang('investor.season.report.season4.individual.title')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($yearItems as $item)
                            @php
                                $cols = [
                                    $item->q1Consolidated,
                                    $item->q2Consolidated,
                                    $item->q3Consolidated,
                                    $item->q4Consolidated,
                                    $item->q4Individual,
                                ];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $item->title }}</td>

                                @foreach ($cols as $col)
                                    <td class="text-center">
                                        @if($col)
                                            <a href="{{ $col }}" download
                                                class="c-icon-wrapper">
                                                <i class="far fa-cloud-arrow-down fa-2x"></i>
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <x-pagination :pagination="$pagination" />
        </section>
    </div>
@stop
