<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\ShareholderInfo;

use Lyrasoft\Investor\Repository\ShareholderInfoRepository;
use Lyrasoft\Luna\Attributes\LangAssoc;
use Unicorn\Aws\S3Service;
use Unicorn\Controller\CrudController;
use Unicorn\Controller\GridController;
use Unicorn\Repository\Event\PrepareSaveEvent;
use Unicorn\Upload\FileUploadOptions;
use Unicorn\Upload\FileUploadService;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Router\Navigator;
use Windwalker\DI\Attributes\Autowire;
use Windwalker\ORM\Event\AfterSaveEvent;

#[Controller()]
class ShareholderInfoController
{
    #[LangAssoc]
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] ShareholderInfoRepository $repository,
        FileUploadService $fileUploadService,
    ): mixed {
        $form = $app->make(ShareholderInfoEditForm::class);

        $controller->prepareSave(
            function (PrepareSaveEvent $event) {
                //
            }
        );

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($app, $fileUploadService, $repository) {
                $data = &$event->data;

                $files = $app->file('item');

                $data['notice_file'] = $fileUploadService->handleFileIfUploaded(
                    $files['notice_file'] ?? null,
                    'files/shareholder-info/' . $data['id'] . '/agm-notice.{ext}',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ,
                        ]
                    )
                )?->getUri(true) ?? $data['notice_file'] ?? null;

                $data['agenda_file'] = $fileUploadService->handleFileIfUploaded(
                    $files['agenda_file'] ?? null,
                    'files/shareholder-info/' . $data['id'] . '/agm-agenda.{ext}',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ,
                        ]
                    )
                )?->getUri(true) ?? $data['agenda_file'] ?? null;

                $data['minutes_file'] = $fileUploadService->handleFileIfUploaded(
                    $files['minutes_file'] ?? null,
                    'files/shareholder-info/' . $data['id'] . '/agm-minutes.{ext}',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ,
                        ]
                    )
                )?->getUri(true) ?? $data['minutes_file'] ?? null;

                $data['primary_shareholder_doc'] = $fileUploadService->handleFileIfUploaded(
                    $files['primary_shareholder_doc'] ?? null,
                    'files/shareholder-info/' . $data['id'] . '/primary-shareholders.{ext}',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ,
                        ]
                    )
                )?->getUri(true) ?? $data['primary_shareholder_doc'] ?? null;

                $repository->save($data);
            }
        );

        $uri = $app->call($controller->saveWithNamespace(...), compact('repository', 'form'));

        return match ($app->input('task')) {
            'save2close' => $nav->to('shareholder_info_list'),
            default => $uri,
        };
    }

    public function delete(
        AppContext $app,
        #[Autowire] ShareholderInfoRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call($controller->delete(...), compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] ShareholderInfoRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call($controller->filter(...), compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] ShareholderInfoRepository $repository,
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
        #[Autowire] ShareholderInfoRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call($controller->copy(...), compact('repository'));
    }
}
