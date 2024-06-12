<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;

readonly class SellCurrency
{
    public function __construct(
        public Currency $sellCurrency,
        public CurrencyAmount $buyCurrencyAmount,
        public BuyExchangeRateValue $buyExchangeRateValue,
        public SellExchangeRateValue $sellExchangeRateValue,
    ) {
    }
}
