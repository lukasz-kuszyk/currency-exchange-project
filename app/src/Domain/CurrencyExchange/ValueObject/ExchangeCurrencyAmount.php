<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;

readonly class ExchangeCurrencyAmount
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

    public function isEqualTo(ExchangeCurrencyAmount $exchangeCurrencyAmount): bool // @todo make unit test
    {
        return $exchangeCurrencyAmount->getFromCurrencyAmount()->isEqualTo($this->fromCurrencyAmount)
            && $exchangeCurrencyAmount->getToCurrencyAmount()->isEqualTo($this->toCurrencyAmount)
            && $exchangeCurrencyAmount->getExchangeRate()->isEqualTo($this->exchangeRate);
    }
}
