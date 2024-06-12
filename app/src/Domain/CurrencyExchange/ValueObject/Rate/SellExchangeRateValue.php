<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate;

readonly class SellExchangeRateValue extends ExchangeRateValue
{
    public function isEqualTo(ExchangeRateValue $exchangeRateValue): bool
    {
        return $exchangeRateValue instanceof SellExchangeRateValue
            && parent::isEqualTo($exchangeRateValue);
    }
}
