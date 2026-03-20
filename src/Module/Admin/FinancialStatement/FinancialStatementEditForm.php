<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\FinancialStatement;

use Lyrasoft\Investor\Entity\FinancialStatement;
use Lyrasoft\Luna\Field\LanguageListField;
use Lyrasoft\Luna\Field\LocaleSwitchField;
use Lyrasoft\Luna\Field\UserModalField;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
use Unicorn\Field\CalendarField;
use Unicorn\Field\FileDragField;
use Unicorn\Field\MultiUploaderField;
use Windwalker\Form\Field\NumberField;
use Unicorn\Field\SwitcherField;
use Unicorn\Enum\BasicState;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Attributes\Fieldset;
use Windwalker\Form\Attributes\FormDefine;
use Windwalker\Form\Attributes\NS;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Form;

class FinancialStatementEditForm
{
    use LocaleAwareTrait;
    use TranslatorTrait;

    #[FormDefine]
    #[NS('item')]
    public function main(Form $form): void
    {
        $form->add('title', TextField::class)
            ->label('年度')
            ->addFilter('trim')
            ->required(true);

        if (WINDWALKER_DEBUG) {
            $form->add('alias', TextField::class)
                ->label($this->trans('unicorn.field.alias'))
                ->addFilter('trim');
        }

        if ($this->isLocaleEnabled()) {
            $form->add('language', LocaleSwitchField::class)
                ->label($this->trans('luna.field.language'))
                ->table(FinancialStatement::class)
                ->canChangeSelfLang(false)
                ->required(true)
                ->allowCreateEmpty(true);
        }

        $form->add('id', HiddenField::class);
    }

    #[FormDefine]
    #[Fieldset('basic')]
    #[NS('item')]
    public function basic(Form $form): void
    {
        $form->add('q1_consolidated', FileDragField::class)
            ->label('第一季合併財報')
            ->showUploaded(true);

        $form->add('q2_consolidated', FileDragField::class)
            ->label('第二季合併財報')
            ->showUploaded(true);

        $form->add('q3_consolidated', FileDragField::class)
            ->label('第三季合併財報')
            ->showUploaded(true);

        $form->add('q4_consolidated', FileDragField::class)
            ->label('第四季合併財報')
            ->showUploaded(true);

        $form->add('q4_individual', FileDragField::class)
            ->label('第四季個體財報')
            ->showUploaded(true);

        $form->add('annual_report', FileDragField::class)
            ->label('公司年報')
            ->showUploaded(true);

        // $form->add('earnings_call_files', MultiUploaderField::class)
        //     ->label('法人說明會')
        //     ->configureForm(
        //         function (Form $form) {
        //             $form->add('title', TextField::class)
        //                 ->label('標題');
        //         }
        //     );
    }

    // meta
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
