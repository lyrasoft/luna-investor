<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        MonthlyReportView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\Investor\Module\Front\MonthlyReport\MonthlyReportView;
use Lyrasoft\Investor\Entity\FinancialStatement;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $item FinancialStatement
 * @var $yearItem FinancialStatement
 */
?>

@push('macro')
    <style data-macro type="text/scss" data-scope=".view-monthly-report">
    </style>

    <script data-macro="monthly.report" lang="ts" type="module">
</script>
@endpush

@extends('global.body')

@section('content')
    <div class="l-monthly-report-page container py-5">
        <x-investor-financial-nav class="mb-5" />

        <section class="">

            <div
                class="d-flex flex-lg-row flex-column justify-content-between align-items-center mb-40px">
                <h2 class="h1 mb-lg-0 mb-20px">
                    @lang('investor.monthly.report.title', year: $item->title)
                </h2>

                <div class="c-report-select">
                    <select class="form-select js-year-select" data-role="year-select">
                        @foreach($yearItems as $yearItem)
                            <option data-dest="{{ $nav->to('investor_monthly_report')->alias($yearItem->title) }}"
                                @attr('selected', $item->id === $yearItem->id)>
                                @lang('investor.monthly.report.title', year: $yearItem->title)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-12px d-lg-none text-end">
                <p class="body-2 text-secondary">Scroll<i class="fa-light fa-arrow-right ms-2"></i></p>
            </div>

            <div class="c-report-table" data-year="113" data-aos="fade-up">
                <div class="table-responsive c-report-table__body">
                    <table class="table table-striped">
                        <thead class="h5">
                        <tr>
                            <th>@lang('investor.monthly.report.form.month.title')</th>
                            <th class="text-end">@lang('investor.monthly.report.form.revenue.title')</th>
                            <th class="text-end">@lang('investor.monthly.report.form.annual.growth.title')</th>
                            <th class="text-end">@lang('investor.monthly.report.form.cumulative.revenue.title')</th>
                            <th class="text-end">@lang('investor.monthly.report.form.cumulative.revenue.annual.growth.title')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php $i = 1; @endphp
                        @foreach($item->monthlyReport as $report)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td class="text-end">{{ $report->revenue }}</td>
                                <td class="text-end">{{ $report->yoy }}</td>
                                <td class="text-end">{{ $report->cumulativeRevenue }}</td>
                                <td class="text-end">{{ $report->cumulativeYoy }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
@stop
