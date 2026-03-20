<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Front\Shareholder;

use Lyrasoft\Investor\Entity\ShareholderInfo;
use Lyrasoft\Investor\Repository\ShareholderInfoRepository;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Request\Input;
use Windwalker\Core\Attributes\ViewMetadata;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Attributes\ViewPrepare;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\View\View;
use Windwalker\DI\Attributes\Autowire;

#[ViewModel(
    layout: [
        'tops' => 'shareholder-tops',
        'meetings' => 'shareholder-meetings',
    ],
    js: 'shareholder.ts'
)]
class ShareholderView
{
    use TranslatorTrait;

    /**
     * Constructor.
     */
    public function __construct(
        #[Autowire]
        protected ShareholderInfoRepository $repository
    )
    {
        //
    }

    #[ViewPrepare]
    public function prepare(AppContext $app, View $view): array
    {
        $route = $app->getMatchedRoute();
        $limit = $route?->getExtraValue('limit') ?: 5;

        $infos = $this->repository->getListSelector()
            ->where('shareholder_info.state', 1)
            ->order('shareholder_info.title', 'DESC')
            ->limit($limit + 1)
            ->all(ShareholderInfo::class);

        $item = $infos->shift();

        return compact('item', 'infos');
    }

    #[ViewMetadata]
    protected function prepareMetadata(HtmlFrame $htmlFrame, #[Input] string $layout): void
    {
        if ($layout === 'tops') {
            $htmlFrame->setTitle(
                $this->trans('investor.shareholder.tops.title')
            );
        } else if ($layout === 'meetings') {
            $htmlFrame->setTitle(
                $this->trans('investor.shareholder.meetings.title')
            );
        }
    }
}
