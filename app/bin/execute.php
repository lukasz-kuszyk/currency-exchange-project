<?php

declare(strict_types=1);

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\ExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\CurrencyExchangeQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;

require 'bootstrap.php';

function printTransaction(ExchangeCurrencyAmount $exchanged, bool $isClientSold): void
{
    echo sprintf(
        'Client %s "%.2f %s" for "%.2f %s" with rate "%.4f"',
        $isClientSold ? 'sold' : 'bought',
        $exchanged->getFromCurrencyAmount()->getAmount(),
        $exchanged->getFromCurrencyAmount()->getCurrency()->getCode(),
        $exchanged->getToCurrencyAmount()->getAmount(),
        $exchanged->getToCurrencyAmount()->getCurrency()->getCode(),
        $exchanged->getExchangeRate()->getRate(),
    ).PHP_EOL;
}

$currencyEUR = new Currency('EUR');
$currencyGBP = new Currency('GBP');

$EURtoGBP = new SellExchangeRateValue(1.5678);
$BGPtoEUR = new BuyExchangeRateValue(1.5432);

printTransaction(
    (new CurrencyExchangeQuota($currencyEUR, $currencyGBP, $BGPtoEUR, $EURtoGBP))
        ->sell(new CurrencyAmount($currencyEUR, 100)),
    true
);

printTransaction(
    (new CurrencyExchangeQuota($currencyEUR, $currencyGBP, $BGPtoEUR, $EURtoGBP))
        ->buy(new CurrencyAmount($currencyEUR, 100)),
    false
);

printTransaction(
    (new CurrencyExchangeQuota($currencyEUR, $currencyGBP, $BGPtoEUR, $EURtoGBP))
        ->sell(new CurrencyAmount($currencyGBP, 100)),
    true
);

printTransaction(
    (new CurrencyExchangeQuota($currencyEUR, $currencyGBP, $BGPtoEUR, $EURtoGBP))
        ->buy(new CurrencyAmount($currencyGBP, 100)),
    false
);
