<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

class Currency
{
    public function __construct(
        public readonly string $code,
    ) {
    }

    public function isEqualTo(Currency $currency): bool
    {
        return $currency->code === $this->code;
    }
}
