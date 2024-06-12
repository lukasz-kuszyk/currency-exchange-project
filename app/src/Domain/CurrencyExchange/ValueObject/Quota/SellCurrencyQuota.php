<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota;

use Brick\Math\BigDecimal;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;

readonly class SellCurrencyQuota extends CurrencyQuota
{
    public static function withSellRate(
        Currency $currencyFrom,
        Currency $currencyTo,
        SellExchangeRateValue $rate,
        float $amount,
    ): SellCurrencyQuota {
        $exchangeAmount = self::calculateAmount($amount, $rate);

        return new SellCurrencyQuota(
            new CurrencyAmount($currencyFrom, $amount),
            new CurrencyAmount($currencyTo, $exchangeAmount),
            $rate,
        );
    }

    public static function withBuyRate(
        Currency $currencyFrom,
        Currency $currencyTo,
        BuyExchangeRateValue $rate,
        float $amount,
    ): SellCurrencyQuota {
        $exchangeAmount = self::calculateAmount($amount, $rate);

        return new SellCurrencyQuota(
            new CurrencyAmount($currencyFrom, $amount),
            new CurrencyAmount($currencyTo, $exchangeAmount),
            $rate
        );
    }

    protected static function calculateAmount(float $amount, ExchangeRateValue $rate): float
    {
        return BigDecimal::of($amount)
            ->multipliedBy($rate->getRate())
            ->toFloat();
    }
}
