<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Entity;

use AllowDynamicProperties;
use Lyrasoft\Investor\Data\MonthlyReport;
use Lyrasoft\Investor\Data\MonthlyReportCollection;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Lyrasoft\Luna\Attributes\Slugify;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\DateTime\ServerTimeCast;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\RouteUri;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\CurrentTime;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\JsonObject;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('financial_statements', 'financial_statement')]
#[AllowDynamicProperties]
class FinancialStatement implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('title')]
    public string $title = '';

    #[Column('alias')]
    #[Slugify]
    public string $alias = '';

    #[Column('q1_consolidated')]
    public string $q1Consolidated = '';

    #[Column('q2_consolidated')]
    public string $q2Consolidated = '';

    #[Column('q3_consolidated')]
    public string $q3Consolidated = '';

    #[Column('q4_consolidated')]
    public string $q4Consolidated = '';

    #[Column('q4_individual')]
    public string $q4Individual = '';

    /**
     * @var MonthlyReportCollection|MonthlyReport[]
     */
    #[Column('monthly_report')]
    #[JsonObject]
    public MonthlyReportCollection $monthlyReport {
        set(MonthlyReportCollection|array|null $value) => $this->monthlyReport = MonthlyReportCollection::wrap($value);
        get => $this->monthlyReport ??= new MonthlyReportCollection();
    }

    #[Column('annual_report')]
    public string $annualReport = '';

    #[Column('earnings_call_files')]
    #[JsonObject]
    public array $earningsCallFiles = [];

    #[Column('state')]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('language')]
    public string $language = '';

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('created')]
    #[CastNullable(ServerTimeCast::class)]
    #[CreatedTime]
    public ?Chronos $created = null {
        set(\DateTimeInterface|string|null $value) => $this->created = Chronos::tryWrap($value);
    }

    #[Column('modified')]
    #[CastNullable(ServerTimeCast::class)]
    #[CurrentTime]
    public ?Chronos $modified = null {
        set(\DateTimeInterface|string|null $value) => $this->modified = Chronos::tryWrap($value);
    }

    #[Column('created_by')]
    #[Author]
    public int $createdBy = 0;

    #[Column('modified_by')]
    #[Modifier]
    public int $modifiedBy = 0;

    #[Column('params')]
    #[JsonObject]
    public array $params = [];

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function monthlyReportLink(Navigator $nav): RouteUri
    {
        return $nav->to('front::investor_monthly_report')->alias($this->alias);
    }
}
