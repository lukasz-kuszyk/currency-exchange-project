<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;

readonly class BuyCurrencyQuota extends CurrencyQuota
{
    public static function withSellRate(
        Currency $currencyFrom,
        Currency $currencyTo,
        SellExchangeRateValue $rate,
        float $amount,
    ): BuyCurrencyQuota {
        $exchangeAmount = self::calculateAmount($amount, $rate);

        return new BuyCurrencyQuota(
            new CurrencyAmount($currencyTo, $amount),
            new CurrencyAmount($currencyFrom, $exchangeAmount),
            $rate
        );
    }

    public static function withBuyRate(
        Currency $currencyFrom,
        Currency $currencyTo,
        BuyExchangeRateValue $rate,
        float $amount,
    ): BuyCurrencyQuota {
        $exchangeAmount = self::calculateAmount($amount, $rate);

        return new BuyCurrencyQuota(
            new CurrencyAmount($currencyTo, $amount),
            new CurrencyAmount($currencyFrom, $exchangeAmount),
            $rate
        );
    }

    protected static function calculateAmount(float $amount, ExchangeRateValue $rate): float
    {
        return BigDecimal::of($amount)
            ->dividedBy($rate->getRate(), 2, RoundingMode::HALF_UP)
            ->toFloat();
    }
}
