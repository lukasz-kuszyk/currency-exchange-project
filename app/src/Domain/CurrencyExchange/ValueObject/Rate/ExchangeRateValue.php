<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate;

use Brick\Math\BigDecimal;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Exception\Rate\ExchangeRateIsZeroException;

/**
 * @throws ExchangeRateIsZeroException
 */
abstract readonly class ExchangeRateValue
{
    /**
     * @param float $rate non-zero float values allowed - also negative
     */
    public function __construct(
        private float $rate
    ) {
        $isZero = BigDecimal::of($this->rate)->isZero();

        if ($isZero) {
            throw new ExchangeRateIsZeroException();
        }
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function isEqualTo(ExchangeRateValue $exchangeRateValue): bool
    {
        return BigDecimal::of($exchangeRateValue->getRate())->isEqualTo($this->rate);
    }
}
