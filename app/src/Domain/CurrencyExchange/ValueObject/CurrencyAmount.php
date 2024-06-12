<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

use Brick\Math\BigDecimal;

readonly class CurrencyAmount
{
    public function __construct(
        private Currency $currency,
        private float $amount, // can be also split into whole parts and decimal for strict calculations
    ) {
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function isEqualTo(CurrencyAmount $currencyAmount): bool
    {
        return $currencyAmount->getCurrency()->isEqualTo($this->currency)
            && BigDecimal::of($currencyAmount->getAmount())->isEqualTo($this->amount);
    }
}
