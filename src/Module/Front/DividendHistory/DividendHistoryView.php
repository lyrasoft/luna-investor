<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Front\DividendHistory;

use Lyrasoft\Investor\Entity\DividendHistory;
use Lyrasoft\Investor\Repository\DividendHistoryRepository;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\ViewMetadata;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Attributes\ViewPrepare;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\View\View;
use Windwalker\DI\Attributes\Autowire;

#[ViewModel(
    layout: 'dividend-history',
    js: 'dividend-history.ts'
)]
class DividendHistoryView
{
    use TranslatorTrait;

    public int $limit = 5;

    /**
     * Constructor.
     */
    public function __construct(
        #[Autowire]
        protected DividendHistoryRepository $repository,
    ) {
        //
    }

    #[ViewPrepare]
    public function prepare(AppContext $app, View $view): array
    {
        $route = $app->getMatchedRoute();
        $this->limit = $route->getExtraValue('limit') ?: 5;

        $items = $this->repository->getListSelector()
            ->where('dividend_history.state', 1)
            ->order('dividend_history.title', 'DESC')
            ->limit($this->limit)
            ->all(DividendHistory::class);

        $limit = $this->limit;

        return compact('items', 'limit');
    }

    #[ViewMetadata]
    protected function prepareMetadata(HtmlFrame $htmlFrame): void
    {
        $htmlFrame->setTitle(
            $this->trans('investor.dividend.history.title', num: $this->limit)
        );
    }
}
