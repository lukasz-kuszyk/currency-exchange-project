<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;

abstract readonly class CurrencyQuota
{
    abstract public static function withSellRate(
        Currency $currencyFrom,
        Currency $currencyTo,
        SellExchangeRateValue $rate,
        float $amount,
    ): CurrencyQuota;

    abstract public static function withBuyRate(
        Currency $currencyFrom,
        Currency $currencyTo,
        BuyExchangeRateValue $rate,
        float $amount,
    ): CurrencyQuota;

    abstract protected static function calculateAmount(float $amount, ExchangeRateValue $rate): float;

    protected function __construct(
        private CurrencyAmount $originalCurrencyAmount,
        private CurrencyAmount $exchangeCurrencyAmount,
        private ExchangeRateValue $exchangeRateValue,
    ) {
    }

    public function getOriginalCurrencyAmount(): CurrencyAmount
    {
        return $this->originalCurrencyAmount;
    }

    public function getExchangeCurrencyAmount(): CurrencyAmount
    {
        return $this->exchangeCurrencyAmount;
    }

    public function getExchangeRateValue(): ExchangeRateValue
    {
        return $this->exchangeRateValue;
    }
}
