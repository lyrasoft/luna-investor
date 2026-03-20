<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Repository;

use Lyrasoft\Investor\Entity\DividendHistory;
use Lyrasoft\Luna\Entity\Language;
use Lyrasoft\Luna\Locale\LocaleAwareTrait;
use Unicorn\Attributes\ConfigureAction;
use Unicorn\Attributes\Repository;
use Unicorn\Repository\Actions\BatchAction;
use Unicorn\Repository\Actions\DeleteAction;
use Unicorn\Repository\Actions\ReorderAction;
use Unicorn\Repository\Actions\SaveAction;
use Unicorn\Repository\ListRepositoryInterface;
use Unicorn\Repository\ListRepositoryTrait;
use Unicorn\Repository\ManageRepositoryInterface;
use Unicorn\Repository\ManageRepositoryTrait;
use Unicorn\Selector\ListSelector;

#[Repository(entityClass: DividendHistory::class)]
class DividendHistoryRepository implements ManageRepositoryInterface, ListRepositoryInterface
{
    use ManageRepositoryTrait;
    use ListRepositoryTrait;
    use LocaleAwareTrait;

    public function getListSelector(): ListSelector
    {
        $selector = $this->createSelector();

        $selector->from(DividendHistory::class);

        if ($this->isLocaleEnabled()) {
            $selector->leftJoin(
                Language::class,
                'lang',
                'lang.code',
                'dividend_history.language'
            );
        }

        return $selector;
    }

    #[ConfigureAction(SaveAction::class)]
    protected function configureSaveAction(SaveAction $action): void
    {
        //
    }

    #[ConfigureAction(ReorderAction::class)]
    protected function configureReorderAction(ReorderAction $action): void
    {
        //
    }

    #[ConfigureAction(BatchAction::class)]
    protected function configureBatchAction(BatchAction $action): void
    {
        //
    }

    #[ConfigureAction(DeleteAction::class)]
    protected function configureDeleteAction(DeleteAction $action): void
    {
        //
    }
}
