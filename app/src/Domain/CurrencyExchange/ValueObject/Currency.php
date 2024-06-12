<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Exception\CurrencyEmptyCodeException;

/**
 * @throws CurrencyEmptyCodeException
 */
readonly class Currency
{
    public function __construct(
        private string $code,
    ) {
        if ('' === $this->code) {
            throw new CurrencyEmptyCodeException();
        }
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isEqualTo(Currency $currency): bool
    {
        return $currency->code === $this->code;
    }
}
