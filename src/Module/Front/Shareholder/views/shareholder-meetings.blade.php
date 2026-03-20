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
 * @var $info ShareholderInfo
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
            @lang('investor.shareholder.meetings.title')
        </h2>

        <section>
            <header>
                <h3>
                    @lang('investor.shareholder.head.meeting', year: $item->title)
                </h3>
            </header>

            <div class="mb-3 pb-4">
                <div class="l-shareholders-meeting__info d-flex align-items-start">
                    <div class="d-flex align-items-center text-nowrap me-1">
                        <i class="fa-regular fa-fw fa-calendar  me-1"></i>
                        <span class="">@lang('investor.shareholder.meeting.label.date')</span>
                    </div>
                    <div>
                        {{ $chronos->toLocalFormat($item->agmDate, 'Y/m/d H:i') }}
                    </div>
                </div>

                <div class="l-shareholders-meeting__info d-flex align-items-start">
                    <div class="d-flex align-items-center text-nowrap me-1">
                        <i class="fa-fw fa-regular fa-location-dot  me-1"></i>
                        <span class="">@lang('investor.shareholder.meeting.label.location')</span>
                    </div>
                    <div>
                        {{ $item->agmLocation }}
                    </div>
                </div>

                @if ($item->agmVideo)
                    <div class="l-shareholders-meeting__info d-flex align-items-center">
                        <i class="fa-fw fa-regular fa-video  me-1"></i>

                        <a href="{{ $item->agmVideo }}" target="_blank">
                            @lang('investor.shareholder.meeting.text.video.link')
                        </a>
                    </div>
                @endif
            </div>
        </section>

        @if ($item->showPrimary)
            <section>
                <header>
                    <h3>
                        @lang('investor.shareholder.head.meeting.history')
                    </h3>
                </header>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">
                            @lang('investor.text.year')
                        </th>
                        <th class="text-center">
                            @lang('investor.shareholder.meeting.field.date')
                        </th>
                        <th class="text-center">
                            @lang('investor.shareholder.meeting.field.notice')
                        </th>
                        <th class="text-center">
                            @lang('investor.shareholder.meeting.field.manual')
                        </th>
                        <th class="text-center">
                            @lang('investor.shareholder.meeting.field.agenda')
                        </th>
                        <th class="text-center">
                            @lang('investor.shareholder.meeting.field.minutes')
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($infos as $info)
                        <tr>
                            <td class="text-center">
                                {{ $info->title }}
                            </td>

                            <td class="text-center">
                                {{ $chronos->toLocalFormat($info->agmDate, 'Y/m/d') }}
                            </td>

                            <td class="text-center">
                                @if ($info->noticeFile)
                                    <a href="{{ $info->noticeFile }}" target="_blank">
                                        <i class="far fa-file-lines fa-2x"></i>
                                    </a>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($info->agendaFile)
                                    <a href="{{ $info->agendaFile }}" target="_blank">
                                        <i class="far fa-file-lines fa-2x"></i>
                                    </a>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($info->minutesFile)
                                    <a href="{{ $info->minutesFile }}" target="_blank">
                                        <i class="far fa-file-lines fa-2x"></i>
                                    </a>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($info->agmVideo)
                                    <a href="{{ $info->agmVideo }}" target="_blank">
                                        <i class="fab fa-youtube fa-2x"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    </div>
@stop
