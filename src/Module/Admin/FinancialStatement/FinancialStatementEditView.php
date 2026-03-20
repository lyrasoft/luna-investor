<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\FinancialStatement;

use Lyrasoft\Attachment\Entity\Attachment;
use Lyrasoft\Investor\Entity\FinancialStatement;
use Lyrasoft\Investor\Repository\FinancialStatementRepository;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
use Unicorn\View\FormAwareViewModelTrait;
use Unicorn\View\ORMAwareViewModelTrait;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewMetadata;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Attributes\ViewPrepare;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\View\View;
use Windwalker\DI\Attributes\Autowire;

/**
 * The FinancialStatementEditView class.
 */
#[ViewModel(
    layout: 'financial-statement-edit',
    js: 'financial-statement-edit.js'
)]
class FinancialStatementEditView
{
    use TranslatorTrait;
    use ORMAwareViewModelTrait;
    use FormAwareViewModelTrait;
    use LocaleAwareTrait;

    public function __construct(
        #[Autowire] protected FinancialStatementRepository $repository,
    ) {
    }

    #[ViewPrepare]
    public function prepare(AppContext $app, View $view): mixed
    {
        $id = $app->input('id');

        /** @var ?FinancialStatement $item */
        $item = $this->repository->getItem($id);

        // Bind item for injection
        $view[FinancialStatement::class] = $item;

        $form = $this->createForm(FinancialStatementEditForm::class)
            ->fillTo('item', $this->orm->extractEntity($item))
            ->fillTo('item', $this->repository->getState()->getAndForget('edit.data'));

        $attachments = $this->orm->from(Attachment::class)
            ->where('type', 'earnings_call')
            ->where('target_id', $item?->id ?? 0)
            ->order('ordering', 'ASC')
            ->all(Attachment::class);

        return compact('form', 'id', 'item', 'attachments');
    }

    #[ViewMetadata]
    protected function prepareMetadata(HtmlFrame $htmlFrame): void
    {
        $htmlFrame->setTitle(
            $this->trans('unicorn.title.edit', title: '財務及法人資訊')
        );
    }
}
