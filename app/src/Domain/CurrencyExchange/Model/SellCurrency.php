<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;

readonly class SellCurrency
{
    public function __construct(
        public Currency $fromCurrency,
        public Currency $toCurrency,
        public ExchangeRateValue $exchangeRateValue,
        public float $amount,
    ) {
    }
}
