# LYRASOFT Investor Package

![Image](https://github.com/user-attachments/assets/b699b7e0-0ae7-49fe-9818-6e09c1e512b5)

## Installation

Install from composer

```shell
composer require lyrasoft/investor
```

Then copy files to project

```shell
php windwalker pkg:install lyrasoft/investor -t routes -t migrations -t seeders
```

### Language Files

Add this line to admin & front middleware if you don't want to override languages:

```php
$this->lang->loadAllFromVendor('lyrasoft/investor', 'ini');

// OR

$this->lang->loadAllFromVendor(\Lyrasoft\Investor\InvestorPackage::class, 'ini');
```

Or run this command to copy languages files:

```
php windwalker pkg:install lyrasoft/investor -t lang
```

### Seeders

Add these files to `main.seeder.php`:

```php
return [
    // ...

    __DIR__ . '/financial-statement.seeder.php',
    __DIR__ . '/shareholder-info.seeder.php',
    __DIR__ . '/dividend-history.seeder.php',

    // ...
];
```


## Register Admin Menu

Edit `resources/menu/admin/sidemenu.menu.php`

You must add `type` to route, every comment should contains type.

```php
// Investor
$menu->link('投資人專區')
    ->to($nav->to('banner_list'))
    ->icon('fal fa-money-bill-trend-up');

$menu->registerChildren(
    function (MenuBuilder $menu) use ($nav, $lang) {
        // Finance
        $menu->link('財務及法人資訊')
            ->to($nav->to('financial_statement_list'))
            ->icon('fal fa-chart-line');

        // Shareholder
        $menu->link('股東專區')
            ->to($nav->to('shareholder_info_list'))
            ->icon('fal fa-user-friends');

        // Dividend
        $menu->link('歷年股利分派')
            ->to($nav->to('dividend_history_list'))
            ->icon('fal fa-coins');
    }
);
```

## Front Available Routes

- `investor_monthly_report` (entity: FinancialStatement)
- `investor_season_report` (entity: FinancialStatement)
- `investor_annual_report` (entity: FinancialStatement)
- `investor_shareholder_tops` (entity: ShareholderInfo)
- `investor_shareholder_meetings` (entity: ShareholderInfo)
- `investor_dividend_history` (entity: DividendHistory)
