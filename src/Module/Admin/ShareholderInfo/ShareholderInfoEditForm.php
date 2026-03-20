<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\ShareholderInfo;

use Lyrasoft\Investor\Entity\ShareholderInfo;
use Lyrasoft\Luna\Field\LocaleSwitchField;
use Lyrasoft\Luna\Field\UserModalField;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
use Unicorn\Field\CalendarField;
use Unicorn\Field\FileDragField;
use Unicorn\Field\RepeatableField;
use Unicorn\Field\SwitcherField;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Attributes\Fieldset;
use Windwalker\Form\Attributes\FormDefine;
use Windwalker\Form\Attributes\NS;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Field\UrlField;
use Windwalker\Form\Form;

class ShareholderInfoEditForm
{
    use TranslatorTrait;
    use LocaleAwareTrait;

    #[FormDefine]
    #[NS('item')]
    public function main(Form $form): void
    {
        $form->add('title', TextField::class)
            ->label('年度')
            ->addFilter('trim')
            ->required(true);

        if ($this->isLocaleEnabled()) {
            $form->add('language', LocaleSwitchField::class)
                ->label($this->trans('luna.field.language'))
                ->table(ShareholderInfo::class)
                ->required(true)
                ->canChangeSelfLang(false)
                ->allowCreateEmpty(true);
        }

        $form->add('id', HiddenField::class);
    }

    #[FormDefine]
    #[Fieldset('agm')]
    #[NS('item')]
    public function agm(Form $form): void
    {
        //
        $form->add('agm_date', CalendarField::class)
            ->label('當年度股東會日期及時間');

        $form->add('agm_location', TextField::class)
            ->label('當年度股東會地點');

        $form->add('agm_video', UrlField::class)
            ->label('股東會直播及完整錄影連結');

        $form->add('notice_file', FileDragField::class)
            ->label('開會通知')
            ->accept('.pdf')
            ->showUploaded(true);

        $form->add('agenda_file', FileDragField::class)
            ->label('議事手冊')
            ->accept('.pdf')
            ->showUploaded(true);

        $form->add('minutes_file', FileDragField::class)
            ->label('議事錄')
            ->accept('.pdf')
            ->showUploaded(true);
    }

    #[FormDefine]
    #[Fieldset('primary_shareholders')]
    #[NS('item')]
    public function primaryShareholders(Form $form): void
    {
        $form->add('show_primary', SwitcherField::class)
            ->label('主要股東顯示於前台')
            ->circle(true)
            ->color('primary');

        $form->add('primary_shareholders', RepeatableField::class)
            ->label('主要股東')
            ->sortable(true)
            ->configureForm(
                function (Form $form) {
                    $form->add('name', TextField::class)
                        ->label('股東名稱');

                    $form->add('shares', TextField::class)
                        ->label('持有股份（股）');

                    $form->add('percentage', TextField::class)
                        ->label('持股比例 (%)');
                }
            );

        $form->add('primary_shareholder_doc', FileDragField::class)
            ->label('主要股東名單關係資料')
            ->accept('.pdf')
            ->showUploaded(true);
    }

    #[FormDefine]
    #[Fieldset('top_shareholders')]
    #[NS('item')]
    public function topShareholders(Form $form): void
    {
        $form->add('top_shareholders', RepeatableField::class)
            ->label('十大股東清單')
            ->sortable(true)
            ->configureForm(
                function (Form $form) {
                    $form->add('name', TextField::class)
                        ->label('股東名稱');

                    $form->add('shares', TextField::class)
                        ->label('持有股份（股）');

                    $form->add('percentage', TextField::class)
                        ->label('持股比例 (%)');
                }
            );
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

        $form->add('created_by', UserModalField::class)
            ->label($this->trans('unicorn.field.author'))
            ->disabled(true);

        $form->add('modified_by', UserModalField::class)
            ->label($this->trans('unicorn.field.modified_by'))
            ->disabled(true);
    }
}
