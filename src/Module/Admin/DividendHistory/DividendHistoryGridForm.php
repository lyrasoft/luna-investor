<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\DividendHistory;

use Lyrasoft\Luna\Field\LanguageListField;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
use Unicorn\Enum\BasicState;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Form\Attributes\FormDefine;
use Windwalker\Form\Attributes\NS;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\SearchField;
use Windwalker\Form\Form;

class DividendHistoryGridForm
{
    use TranslatorTrait;
    use LocaleAwareTrait;

    #[FormDefine]
    #[NS('search')]
    public function search(Form $form): void
    {
        $form->add('*', SearchField::class)
            ->label($this->trans('unicorn.grid.search.label'))
            ->placeholder($this->trans('unicorn.grid.search.label'))
            ->onchange('this.form.submit()');
    }

    #[FormDefine]
    #[NS('filter')]
    public function filter(Form $form): void
    {
        $form->add('dividend_history.state', ListField::class)
            ->label($this->trans('unicorn.field.state'))
            ->option($this->trans('unicorn.select.placeholder'), '')
            ->registerFromEnums(BasicState::class, $this->lang)
            ->onchange('this.form.submit()');

        if ($this->isLocaleEnabled()) {
            $form->add('dividend_history.language', LanguageListField::class)
                ->label($this->trans('luna.field.language'))
                ->option($this->trans('unicorn.select.placeholder'), '')
                ->onchange('this.form.submit()');
        }
    }

    #[FormDefine]
    #[NS('batch')]
    public function batch(Form $form): void
    {
        $form->add('state', ListField::class)
            ->label($this->trans('unicorn.field.state'))
            ->option($this->trans('unicorn.select.no.change'), '')
            ->registerFromEnums(BasicState::class, $this->lang);
    }
}
