<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Entity;

use AllowDynamicProperties;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
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
#[Table('dividend_histories', 'dividend_history')]
#[AllowDynamicProperties]
class DividendHistory implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('title')]
    public string $title = '';

    #[Column('stock_dividend')]
    public float $stockDividend = 0.0;

    #[Column('cash_dividend')]
    public float $cashDividend = 0.0;

    #[Column('ex_dividend_date')]
    #[CastNullable(ServerTimeCast::class)]
    public ?Chronos $exDividendDate = null {
        set(\DateTimeInterface|string|null $value) => $this->exDividendDate = Chronos::tryWrap($value);
    }

    #[Column('record_date')]
    #[CastNullable(ServerTimeCast::class)]
    public ?Chronos $recordDate = null {
        set(\DateTimeInterface|string|null $value) => $this->recordDate = Chronos::tryWrap($value);
    }

    #[Column('payment_date')]
    #[CastNullable(ServerTimeCast::class)]
    public ?Chronos $paymentDate = null {
        set(\DateTimeInterface|string|null $value) => $this->paymentDate = Chronos::tryWrap($value);
    }

    #[Column('state')]
    public BasicState $state {
        set(BasicState|int $value) => $this->state = BasicState::wrap($value);
    }

    #[Column('ordering')]
    public int $ordering = 0;

    #[Column('language')]
    public string $language = '';

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

    public function makeLink(Navigator $nav): RouteUri
    {
        return $nav->to('front::investor_dividend_history')->alias($this->title);
    }
}
