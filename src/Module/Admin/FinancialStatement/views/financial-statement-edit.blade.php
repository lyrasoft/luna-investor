<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        FinancialStatementEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use App\Data\Financial\MonthlyReport;
use Lyrasoft\Investor\Entity\FinancialStatement;
use Lyrasoft\Investor\Module\Admin\FinancialStatement\FinancialStatementEditView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Filesystem\Path;
use Windwalker\Form\Form;

/**
 * @var $form Form
 * @var $item FinancialStatement
 */
?>

@push('macro')
    <style data-macro type="text/scss" data-scope=".view-financial-statement-edit">
    </style>

    <script data-macro="admin.financial.statement.edit" lang="ts" type="module">
</script>
@endpush

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('financial_statement_edit') }}"
        method="POST" enctype="multipart/form-data">

        <x-title-bar :form="$form" ns="item">
            @if ($vm->isLocaleEnabled())
                <x-slot name="end">
                        <?php
                        $form['item/language']->currentId($item?->id) ?>
                    <x-field :field="$form['item/language']" no-label></x-field>
                </x-slot>
            @endif

        </x-title-bar>

        <div class="card mb-3">
            <div class="card-body edit-tab-nav p-2">
            </div>
        </div>

        <x-tabs keepactive variant="pills" nav-target=".edit-tab-nav">
            <x-tab id="financial" title="財報與法人資訊檔案">
                <div class="row">
                    <div class="col-lg-7">
                        <x-fieldset name="basic" :title="$lang('unicorn.fieldset.basic')"
                            :form="$form"
                            class="mb-4"
                            is="card"
                        >
                        </x-fieldset>
                        <div class="card p-3">
                            <h4 class="card-title mb-4">
                                法人說明會
                            </h4>
                            <div class="card-body">
                                <x-attachment-field
                                    :items="$attachments"
                                    :insertBtn="false"
                                    :accept="'pdf'"
                                    :mutiple="true"
                                    :sortable="true"
                                >
                                </x-attachment-field>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <x-fieldset name="meta" :title="$lang('unicorn.fieldset.meta')"
                            :form="$form"
                            class="mb-4"
                            is="card"
                        >
                        </x-fieldset>
                    </div>
                </div>
            </x-tab>

            <x-tab id="profit" title="損益資訊">
                <div>
                    @php
                        $months = range(1, 12);
                    @endphp
                    <x-card title="每月營收及摘要">
                        <div class="mb-3 text-muted">
                            皆為純文字欄位，需自行填寫千位數分隔符號 (,) 與百分比 (%)
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 1%">月份</th>
                                    <th style="width: 175px;">
                                        營收(KNTD)
                                    </th>
                                    <th style="width: 175px;">
                                        年增(減)率%
                                    </th>
                                    <th style="width: 175px;">
                                        累計營收(KNTD)
                                    </th>
                                    <th style="width: 175px;">
                                        累計營收年增(減)率%
                                    </th>
                                    @if(WINDWALKER_DEBUG)
                                        <th>
                                            月營收摘要
                                        </th>
                                    @endif
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($months as $month)
                                    @php($monthlyReport = $item->monthlyReport[$month] ?? new MonthlyReport())

                                    <tr>
                                        <td>{{ $month }}</td>
                                        <td>
                                            <input
                                                type="text"
                                                name="item[monthly_report][{{ $month }}][revenue]"
                                                class="form-control"
                                                value="{{ $monthlyReport->revenue ?? '' }}"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                name="item[monthly_report][{{ $month }}][yoy]"
                                                class="form-control"
                                                value="{{ $monthlyReport->yoy ?? '' }}"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                name="item[monthly_report][{{ $month }}][cumulativeRevenue]"
                                                class="form-control"
                                                value="{{ $monthlyReport->cumulativeRevenue ?? '' }}"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text"
                                                name="item[monthly_report][{{ $month }}][cumulativeYoy]"
                                                class="form-control"
                                                value="{{ $monthlyReport->cumulativeYoy ?? '' }}"
                                            />
                                        </td>

                                        @if(WINDWALKER_DEBUG)
                                            <td>
                                                <div class="d-flex gap-3">
                                                    <input
                                                        type="file"
                                                        name="item[monthly_report][{{ $month }}][revenueSummary]"
                                                        class="form-control"
                                                        accept=".pdf"
                                                        style="min-width: 250px"
                                                    />

                                                    @if ($monthlyReport->revenueSummary)
                                                        <div class="input-group flex-nowrap">
                                                            <div class="input-group-text">
                                                                <span class="far fa-file-pdf"></span>
                                                                <div class="text-truncate mx-2" style="max-width: 200px"
                                                                    data-bs-toggle="tooltip"
                                                                    title="{{ Path::getFilename($monthlyReport->revenueSummary) }}"
                                                                >
                                                                    <a href="{{ $monthlyReport->revenueSummary }}"
                                                                        target="_blank" rel="noopener">
                                                                        {{ Path::getFilename($monthlyReport->revenueSummary) }}
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <div class="input-group-text">
                                                                <div class="form-check m-0" style="min-height: 18px;">
                                                                    <input
                                                                        type="checkbox"
                                                                        name="item[monthly_report][{{ $month }}][removeRevenueSummary]"
                                                                        class="form-check-input"
                                                                        id="remove-revenue-summary-{{ $month }}"
                                                                        style="height: 20px;"
                                                                    />
                                                                    <label
                                                                        for="remove-revenue-summary-{{ $month }}"
                                                                        class="form-check-label mb-0"
                                                                    >
                                                                        刪除
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>
            </x-tab>
        </x-tabs>

        <div class="d-none">
            @if ($idField = $form?->getField('item/id') ?? $form?->getField('id'))
                <input name="{{ $idField->getInputName() }}" type="hidden" value="{{ $idField->getValue() }}" />
            @endif

            <x-csrf></x-csrf>
        </div>
    </form>
@stop
