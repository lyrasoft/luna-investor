<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\DividendHistory;

use Lyrasoft\Investor\Entity\DividendHistory;
use Lyrasoft\Luna\Field\LocaleSwitchField;
use Lyrasoft\Luna\Field\UserModalField;
use Unicorn\Field\SwitcherField;
use Unicorn\Field\CalendarField;
use Windwalker\Form\Field\NumberField;
use Unicorn\Enum\BasicState;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Attributes\Fieldset;
use Windwalker\Form\Attributes\FormDefine;
use Windwalker\Form\Attributes\NS;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Form;

class DividendHistoryEditForm
{
    use TranslatorTrait;

    #[FormDefine]
    #[NS('item')]
    public function main(Form $form): void
    {
        $form->add('title', TextField::class)
            ->label('年度')
            ->addFilter('trim')
            ->required(true);

        $form->add('id', HiddenField::class);
    }

    #[FormDefine]
    #[Fieldset('basic')]
    #[NS('item')]
    public function basic(Form $form): void
    {
        $form->add('cash_dividend', NumberField::class)
            ->label('現金股利')
            ->step('0.00000001');

        $form->add('stock_dividend', NumberField::class)
            ->label('股票股利')
            ->step('0.00000001');

        $form->add('ex_dividend_date', CalendarField::class)
            ->label('除權息交易日')
            ->enableTime(false)
            ->format(CalendarField::FORMAT_DATE);

        $form->add('record_date', CalendarField::class)
            ->label('基準日')
            ->enableTime(false)
            ->format(CalendarField::FORMAT_DATE);

        $form->add('payment_date', CalendarField::class)
            ->label('股利發放日')
            ->enableTime(false)
            ->format(CalendarField::FORMAT_DATE);
    }

    #[FormDefine]
    #[Fieldset('meta')]
    #[NS('item')]
    public function meta(Form $form): void
    {
        $form->add('state', SwitcherField::class)
            ->label($this->trans('unicorn.field.published'))
            ->circle(true)
            ->color('success')
            ->defaultValue('1');

        $form->add('created', CalendarField::class)
            ->label($this->trans('unicorn.field.created'))
            ->disabled(true);

        $form->add('modified', CalendarField::class)
            ->label($this->trans('unicorn.field.modified'))
            ->disabled(true);

        if (WINDWALKER_DEBUG) {
            $form->add('created_by', UserModalField::class)
                ->label($this->trans('unicorn.field.author'))
                ->disabled(true);
        }

        $form->add('modified_by', UserModalField::class)
            ->label($this->trans('unicorn.field.modified_by'))
            ->disabled(true);
    }
}
