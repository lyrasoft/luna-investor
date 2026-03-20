<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Front\FinancialReport;

use Lyrasoft\Investor\Entity\FinancialStatement;
use Lyrasoft\Investor\Repository\FinancialStatementRepository;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
use Lyrasoft\Luna\Services\AssociationService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Request\Input;
use Windwalker\Core\Attributes\ViewMetadata;
use Windwalker\Core\Attributes\ViewModel;
use Windwalker\Core\Attributes\ViewPrepare;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Router\Exception\RouteNotFoundException;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\View\View;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\ORM;
use Windwalker\Query\Query;

#[ViewModel(
    layout: [
        'monthly' => 'financial-monthly-report',
        'season' => 'financial-season-report',
        'annual' => 'financial-annual-report',
    ],
    js: [
        'financial-monthly-report' => 'financial-monthly-report.ts',
        'season' => 'financial-season-report.ts',
        'annual' => 'financial-annual-report.ts',
    ]
)]
class FinancialReportView
{
    use LocaleAwareTrait;
    use TranslatorTrait;

    /**
     * Constructor.
     */
    public function __construct(
        protected ORM $orm,
        #[Autowire]
        protected FinancialStatementRepository $repository,
        protected AssociationService $associationService,
        protected Navigator $nav,
    ) {
        //
    }

    #[ViewPrepare]
    public function prepare(AppContext $app, View $view)
    {
        $layout = $app->input('layout');

        $locale = $this->getLocale();
        $limit = 10;
        $page = $app->input('page');

        $selector = $this->repository->getListSelector()
            ->tapIf(
                $this->isLocaleEnabled(),
                fn (Query $query) => $query->where('financial_statement.language', 'in', [$locale, '*'])
            )
            ->where('financial_statement.state', 1)
            ->order('financial_statement.alias', 'DESC')
            ->page($page)
            ->limit($limit);

        $pagination = $selector->getPagination();

        $yearItems = $selector->all(FinancialStatement::class);
        $item = null;

        if ($layout === 'monthly') {
            $view->setLayout('financial-monthly-report');

            $alias = $app->input('alias');

            if (!$alias) {
                /** @var FinancialStatement $item */
                $item = $yearItems->first();

                if (!$item) {
                    throw new RouteNotFoundException('No financial statement found.');
                }

                return $item->monthlyReportLink($this->nav);
            }

            $items = $this->repository->getListSelector()
                ->where('financial_statement.state', 1)
                ->order('financial_statement.alias', 'DESC')
                ->all(FinancialStatement::class);

            if ($this->isLocaleEnabled()) {
                $item = $items->findFirst(fn (FinancialStatement $item) => $item->language === '*' || $item->language === $locale);
            } else {
                $item = $items->findFirst(fn (FinancialStatement $item) => $item->alias === $alias);
            }

            $view[FinancialStatement::class] = $item;
        } elseif ($layout === 'season') {
            $view->setLayout('financial-season-report');
        } elseif ($layout === 'annual') {
            $view->setLayout('financial-annual-report');
        }

        return compact(
            'item',
            'yearItems',
            'pagination'
        );
    }

    #[ViewMetadata]
    protected function prepareMetadata(HtmlFrame $htmlFrame, #[Input] string $layout, ?FinancialStatement $item = null): void
    {
        if ($layout === 'monthly') {
            $htmlFrame->setTitle(
                $this->trans('investor.monthly.report.title', year: $item->title)
            );
        } elseif ($layout === 'season') {
            $htmlFrame->setTitle(
                $this->trans('investor.financial.season.report.title')
            );
        } else {
            $htmlFrame->setTitle(
                $this->trans('investor.financial.annual.report.title')
            );
        }
    }
}
