<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        DividendHistoryView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\Investor\Module\Front\DividendHistory\DividendHistoryView;
use Lyrasoft\Investor\Entity\DividendHistory;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var DividendHistory $item
 */
?>

@push('macro')
    <style data-macro type="text/scss" data-scope=".view-dividend-history">
    </style>

    <script data-macro="dividend.history" lang="ts" type="module">
</script>
@endpush

@extends('global.body')

@section('content')

   <div class="l-dividend-history-page container py-5">
       <x-investor-financial-nav class="mb-5" />

       <section class="">
           <h2 class="text-center mb-4">
               @lang('investor.dividend.history.title', num: $limit)
           </h2>


           <table class="table table-striped">
               <thead>
               <tr>
                   <th>
                       @lang('investor.text.year')
                   </th>
                   <th class="text-end">
                       @lang('investor.dividend.field.stock.dividend')
                   </th>
                   <th class="text-end">
                       @lang('investor.dividend.field.cash.dividend')
                   </th>
                   <th class="text-center">
                       @lang('investor.dividend.field.ex.dividend.date')
                   </th>
                   <th class="text-center">
                       @lang('investor.dividend.field.record.date')
                   </th>
                   <th class="text-center">
                       @lang('investor.dividend.field.payment.date')
                   </th>
               </tr>
               </thead>
               <tbody>
               @foreach ($items as $item)
                <tr>
                    <td>
                        {{ $item->title }}
                    </td>
                    <td class="text-end">
                        {{ $item->stockDividend }}
                    </td>
                    <td class="text-end">
                        {{ $item->cashDividend }}
                    </td>
                    <td class="text-center">
                        {{ $chronos->toLocalFormat($item->exDividendDate, 'Y/m/d') }}
                    </td>
                    <td class="text-center">
                        {{ $chronos->toLocalFormat($item->recordDate, 'Y/m/d') }}
                    </td>
                    <td class="text-center">
                        {{ $chronos->toLocalFormat($item->paymentDate, 'Y/m/d') }}
                    </td>
                </tr>
               @endforeach
               </tbody>
           </table>


   </section>

@stop
