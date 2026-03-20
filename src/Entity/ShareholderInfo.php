<?php

declare(strict_types=1);

namespace Lyrasoft\Investor\Entity;

use AllowDynamicProperties;
use Lyrasoft\Investor\Data\Shareholder;
use Lyrasoft\Investor\Data\ShareholderCollection;
use Lyrasoft\Luna\Attributes\Author;
use Lyrasoft\Luna\Attributes\Modifier;
use Unicorn\Enum\BasicState;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\DateTime\ServerTimeCast;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
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
#[Table('shareholder_infos', 'shareholder_info')]
#[AllowDynamicProperties]
class ShareholderInfo implements EntityInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('title')]
    public string $title = '';

    #[Column('agm_date')]
    #[CastNullable(ServerTimeCast::class)]
    public ?Chronos $agmDate = null {
        set(\DateTimeInterface|string|null $value) => $this->agmDate = Chronos::tryWrap($value);
    }

    #[Column('agm_location')]
    public string $agmLocation = '';

    #[Column('agm_video')]
    public string $agmVideo = '';

    /**
     * @var ShareholderCollection|Shareholder[]
     */
    #[Column('top_shareholders')]
    #[JsonObject]
    public ShareholderCollection $topShareholders {
        get => $this->topShareholders ??= new ShareholderCollection();
        set(ShareholderCollection|array|null $value) => $this->topShareholders = ShareholderCollection::wrap($value);
    }

    /**
     * @var ShareholderCollection|Shareholder[]
     */
    #[Column('primary_shareholders')]
    #[JsonObject]
    public ShareholderCollection $primaryShareholders {
        set(ShareholderCollection|array|null $value) => $this->primaryShareholders = ShareholderCollection::wrap($value);
        get => $this->primaryShareholders ??= new ShareholderCollection();
    }

    #[Column('primary_shareholder_doc')]
    public string $primaryShareholderDoc = '';

    #[Column('show_primary')]
    #[Cast('bool', 'int')]
    public bool $showPrimary = false;

    #[Column('notice_file')]
    public string $noticeFile = '';

    #[Column('agenda_file')]
    public string $agendaFile = '';

    #[Column('minutes_file')]
    public string $minutesFile = '';

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
}
