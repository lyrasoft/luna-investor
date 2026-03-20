<?php

declare(strict_types=1);

namespace Lyrasoft\Investor;

use Lyrasoft\Investor\Component\InvestorFinancialNavComponent;
use Lyrasoft\Investor\Entity\DividendHistory;
use Lyrasoft\Investor\Entity\FinancialStatement;
use Lyrasoft\Investor\Entity\ShareholderInfo;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageInstaller;
use Windwalker\Core\Renderer\RendererService;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Utilities\Iterator\PriorityQueue;

class InvestorPackage extends AbstractPackage implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->mergeParameters(
            'renderer.edge.components',
            [
                'investor-financial-nav' => InvestorFinancialNavComponent::class,
            ]
        );

        $container->extend(RendererService::class, function (RendererService $rendererService) {
            $rendererService->addPath(static::path('views'), PriorityQueue::BELOW_NORMAL);
        });
    }

    public function install(PackageInstaller $installer): void
    {
        $installer->installConfig(static::path('etc/*.php'), 'config');
        $installer->installLanguages(static::path('resources/languages/**/*.ini'), 'lang');
        $installer->installMigrations(static::path('resources/migrations/**/*'), 'migrations');
        $installer->installSeeders(static::path('resources/seeders/**/*'), 'seeders');
        $installer->installRoutes(static::path('routes/**/*.php'), 'routes');

        $installer->installMVCModules(FinancialStatement::class);
        $installer->installMVCModules(DividendHistory::class);
        $installer->installMVCModules(ShareholderInfo::class);
    }
}
