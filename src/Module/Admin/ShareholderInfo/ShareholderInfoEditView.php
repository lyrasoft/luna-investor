<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\ShareholderInfo;

use Lyrasoft\Investor\Entity\ShareholderInfo;
use Lyrasoft\Investor\Repository\ShareholderInfoRepository;
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
 * The ShareholderInfoEditView class.
 */
#[ViewModel(
    layout: 'shareholder-info-edit',
    js: 'shareholder-info-edit.js'
)]
class ShareholderInfoEditView
{
    use TranslatorTrait;
    use ORMAwareViewModelTrait;
    use FormAwareViewModelTrait;
    use LocaleAwareTrait;

    public function __construct(
        #[Autowire] protected ShareholderInfoRepository $repository,
    ) {
    }

    #[ViewPrepare]
    public function prepare(AppContext $app, View $view): mixed
    {
        $id = $app->input('id');

        /** @var ?ShareholderInfo $item */
        $item = $this->repository->getItem($id);

        // Bind item for injection
        $view[ShareholderInfo::class] = $item;

        $form = $this->createForm(ShareholderInfoEditForm::class)
            ->fillTo('item', $this->orm->extractEntity($item))
            ->fillTo('item', $this->repository->getState()->getAndForget('edit.data'));

        return compact('form', 'id', 'item');
    }

    #[ViewMetadata]
    protected function prepareMetadata(HtmlFrame $htmlFrame): void
    {
        $htmlFrame->setTitle(
            $this->trans('unicorn.title.edit', title: '股東專區')
        );
    }
}
