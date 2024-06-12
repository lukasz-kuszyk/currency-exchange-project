<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

readonly class FinalExchangeCurrencyAmount
{
    public function __construct(
        private ExchangeCurrencyAmount $beforeExchangedCurrencyAmount,
        private CurrencyAmount $differenceCurrencyAmount,
        private CurrencyAmount $finalCurrencyAmount,
    ) {
    }

    public function getBeforeExchangedCurrencyAmount(): ExchangeCurrencyAmount
    {
        return $this->beforeExchangedCurrencyAmount;
    }

    public function getDifferenceCurrencyAmount(): CurrencyAmount
    {
        return $this->differenceCurrencyAmount;
    }

    public function getFinalCurrencyAmount(): CurrencyAmount
    {
        return $this->finalCurrencyAmount;
    }
}
