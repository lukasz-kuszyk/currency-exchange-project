<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;

readonly class ExchangedCurrencyAmount
{
    public function __construct(
        private CurrencyAmount $fromCurrencyAmount,
        private CurrencyAmount $toCurrencyAmount,
        private ExchangeRateValue $exchangeRate,
    ) {
    }

    public function getFromCurrencyAmount(): CurrencyAmount
    {
        return $this->fromCurrencyAmount;
    }

    public function getToCurrencyAmount(): CurrencyAmount
    {
        return $this->toCurrencyAmount;
    }

    public function getExchangeRate(): ExchangeRateValue
    {
        return $this->exchangeRate;
    }
}
