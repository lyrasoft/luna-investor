<?php

declare(strict_types=1);

namespace App\View;

/**
 * Global variables
 * --------------------------------------------------------------
 * @var  $app       AppContext      Application context.
 * @var  $vm        ShareholderInfoEditView  The view model object.
 * @var  $uri       SystemUri       System Uri information.
 * @var  $chronos   ChronosService  The chronos datetime service.
 * @var  $nav       Navigator       Navigator object to build route.
 * @var  $asset     AssetService    The Asset manage service.
 * @var  $lang      LangService     The language translation service.
 */

use Lyrasoft\Investor\Entity\ShareholderInfo;
use Lyrasoft\Investor\Module\Admin\ShareholderInfo\ShareholderInfoEditView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;
use Windwalker\Form\Form;

/**
 * @var $form Form
 * @var $item ShareholderInfo
 */
?>

@push('macro')
    <style data-macro type="text/scss" data-scope=".view-shareholder-info-edit">
    </style>

    <script data-macro="admin.shareholder.info.edit" lang="ts" type="module">
</script>
@endpush

@extends('admin.global.body-edit')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
    <form name="admin-form" id="admin-form"
        uni-form-validate='{"scroll": true}'
        action="{{ $nav->to('shareholder_info_edit') }}"
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

        <div class="row">
            <div class="col-lg-7">
                <x-fieldset name="top_shareholders" title="十大股東"
                    :form="$form"
                    class="mb-4"
                    is="card"
                >
                </x-fieldset>
                <x-fieldset name="primary_shareholders" title="股權比例達百分之五之主要股東"
                    :form="$form"
                    class="mb-4"
                    is="card"
                >
                </x-fieldset>
            </div>
            <div class="col-lg-5">
                <x-fieldset name="agm" title="股東會資訊"
                    :form="$form"
                    class="mb-4"
                    is="card"
                >
                </x-fieldset>
                <x-fieldset name="meta" :title="$lang('unicorn.fieldset.meta')"
                    :form="$form"
                    class="mb-4"
                    is="card"
                >
                </x-fieldset>
            </div>
        </div>

        <div class="d-none">
            @if ($idField = $form?->getField('item/id') ?? $form?->getField('id'))
                <input name="{{ $idField->getInputName() }}" type="hidden" value="{{ $idField->getValue() }}" />
            @endif

            <x-csrf></x-csrf>
        </div>
    </form>
@stop
