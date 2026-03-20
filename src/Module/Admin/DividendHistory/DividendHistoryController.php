<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\DividendHistory;

use Lyrasoft\Investor\Repository\DividendHistoryRepository;
use Lyrasoft\Luna\Attributes\LangAssoc;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Repository\Event\PrepareSaveEvent;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;

#[Controller()]
class DividendHistoryController
{
    #[LangAssoc]
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] DividendHistoryRepository $repository,
    ): mixed {
        $form = $app->make(DividendHistoryEditForm::class);

        $controller->prepareSave(
            function (PrepareSaveEvent $event) {
                //
            }
        );

        $controller->afterSave(
            function (AfterSaveEvent $event) {
                //
            }
        );

        $uri = $app->call($controller->saveWithNamespace(...), compact('repository', 'form'));

        return match ($app->input('task')) {
            'save2close' => $nav->to('dividend_history_list'),
            default => $uri,
        };
    }

    public function delete(
        AppContext $app,
        #[Autowire] DividendHistoryRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call($controller->delete(...), compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] DividendHistoryRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call($controller->filter(...), compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] DividendHistoryRepository $repository,
        GridController $controller
    ): mixed {
        $task = $app->input('task');
        $data = match ($task) {
            'publish' => ['state' => 1],
            'unpublish' => ['state' => 0],
            default => null
        };

        return $app->call($controller->batch(...), compact('repository', 'data'));
    }

    public function copy(
        AppContext $app,
        #[Autowire] DividendHistoryRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call($controller->copy(...), compact('repository'));
    }
}
