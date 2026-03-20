<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Module\Admin\FinancialStatement;

use App\Entity\Attachment;
use Lyrasoft\Investor\Repository\FinancialStatementRepository;
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
use Windwalker\ORM\ORM;

#[Controller()]
class FinancialStatementController
{
    #[LangAssoc]
    public function save(
        AppContext $app,
        CrudController $controller,
        Navigator $nav,
        #[Autowire] FinancialStatementRepository $repository,
        FileUploadService $fileUploadService,
        ORM $orm,
    ): mixed {
        $form = $app->make(FinancialStatementEditForm::class);

        $controller->prepareSave(
            function (PrepareSaveEvent $event) use ($app) {
                $event->data['monthly_report'] = $app->input('item')['monthly_report'];
            }
        );

        $controller->afterSave(
            function (AfterSaveEvent $event) use ($repository, $app, $fileUploadService, $orm) {
                $data = &$event->data;

                $files = $app->file('item');

                $data['q1_consolidated'] = $fileUploadService->handleFileIfUploaded(
                    $files['q1_consolidated'] ?? null,
                    'files/financial-statements/' . $data['id'] . '/q1-consolidated.pdf',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ
                        ]
                    )
                )?->getUri(true) ?? $data['q1_consolidated'] ?? null;

                $data['q2_consolidated'] = $fileUploadService->handleFileIfUploaded(
                    $files['q2_consolidated'] ?? null,
                    'files/financial-statements/' . $data['id'] . '/q2-consolidated.pdf',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ
                        ]
                    )
                )?->getUri(true) ?? $data['q2_consolidated'] ?? null;

                $data['q3_consolidated'] = $fileUploadService->handleFileIfUploaded(
                    $files['q3_consolidated'] ?? null,
                    'files/financial-statements/' . $data['id'] . '/q3-consolidated.pdf',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ
                        ]
                    )
                )?->getUri(true) ?? $data['q3_consolidated'] ?? null;

                $data['q4_consolidated'] = $fileUploadService->handleFileIfUploaded(
                    $files['q4_consolidated'] ?? null,
                    'files/financial-statements/' . $data['id'] . '/q4-consolidated.pdf',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ
                        ]
                    )
                )?->getUri(true) ?? $data['q4_consolidated'] ?? null;

                $data['q4_individual'] = $fileUploadService->handleFileIfUploaded(
                    $files['q4_individual'] ?? null,
                    'files/financial-statements/' . $data['id'] . '/q4-individual.pdf',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ
                        ]
                    )
                )?->getUri(true) ?? $data['q4_individual'] ?? null;

                $data['annual_report'] = $fileUploadService->handleFileIfUploaded(
                    $files['annual_report'] ?? null,
                    'files/financial-statements/' . $data['id'] . '/annual-report.pdf',
                    new FileUploadOptions(
                        storageOptions: [
                            // If use S3
                            'acl' => S3Service::ACL_PUBLIC_READ
                        ]
                    )
                )?->getUri(true) ?? $data['annual_report'] ?? null;

                $data['monthly_report'] = json_decode($data['monthly_report'], true) ?? [];

                // Monthly report
                foreach (range(1, 12) as $month) {
                    if ($app->input('item')['monthly_report'][$month]['removeRevenueSummary'] ?? 0) {
                        $data['monthly_report'][$month]['revenueSummary'] = null;
                    }

                    $data['monthly_report'][$month]['revenueSummary'] = $fileUploadService->handleFileIfUploaded(
                        $files['monthly_report'][$month]['revenueSummary'] ?? null,
                        'files/financial-statements/' . $data['id'] . "/monthly-report-{$month}.pdf",
                        new FileUploadOptions(
                            storageOptions: [
                                // If use S3
                                'acl' => S3Service::ACL_PUBLIC_READ
                            ]
                        )
                    )?->getUri(true)?->__toString() ?? $data['monthly_report'][$month]['revenueSummary'] ?? '';
                }

                // attachments
                $existsAttachments = (array) $app->input('attachments');

                foreach ($existsAttachments as $i => $id) {
                    $orm->updateWhere(
                        Attachment::class,
                        [
                            'ordering' => $i,
                        ],
                        ['id' => $id]
                    );
                }

                $attachments = $app->file('attachments');
                $removeAttachments = $app->input('remove_attachments') ?? [];

                foreach ($removeAttachments as $removeAttachment) {
                    $orm->deleteWhere(Attachment::class, ['id' => $removeAttachment]);
                }

                foreach ($attachments as $k => $attachment) {
                    $url = $fileUploadService->handleFileIfUploaded(
                        $attachment ?? null,
                        'financial_statement/' . md5((string) $data['id']) . '.{ext}'
                    )?->getUri(true);
                    if ($url) {
                        $item = new Attachment();

                        $item->type = 'file';
                        $item->kind = 'financial_file';
                        $item->permission = 'default';
                        $item->targetId = (int) $data['id'];
                        $item->title = $attachment->getClientFilename();
                        $item->size = $attachment->getSize();
                        $item->path = (string) $url;
                        $item->state = 1;
                        $item->ordering = count($existsAttachments) + $k + 1;

                        $orm->createOne(Attachment::class, $item);
                    }
                }

                $repository->save($data);
            }
        );

        $uri = $app->call($controller->saveWithNamespace(...), compact('repository', 'form'));

        return match ($app->input('task')) {
            'save2close' => $nav->to('financial_statement_list'),
            default => $uri,
        };
    }

    public function delete(
        AppContext $app,
        #[Autowire] FinancialStatementRepository $repository,
        CrudController $controller
    ): mixed {
        return $app->call($controller->delete(...), compact('repository'));
    }

    public function filter(
        AppContext $app,
        #[Autowire] FinancialStatementRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call($controller->filter(...), compact('repository'));
    }

    public function batch(
        AppContext $app,
        #[Autowire] FinancialStatementRepository $repository,
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
        #[Autowire] FinancialStatementRepository $repository,
        GridController $controller
    ): mixed {
        return $app->call($controller->copy(...), compact('repository'));
    }
}
