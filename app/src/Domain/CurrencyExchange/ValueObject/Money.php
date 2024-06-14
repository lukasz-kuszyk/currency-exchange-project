<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

use Brick\Math\BigDecimal;

class Money
{
    public function __construct(
        public readonly Currency $currency,
        public readonly float $amount,
    ) {
    }

    public function isEqualTo(Money $money): bool
    {
        return $money->currency->isEqualTo($this->currency)
            && BigDecimal::of($money->amount)->isEqualTo($this->amount);
    }
}
