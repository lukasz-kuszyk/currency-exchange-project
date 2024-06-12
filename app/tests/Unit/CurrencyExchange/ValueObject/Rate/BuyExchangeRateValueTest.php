<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\ValueObject\Rate;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;

final class BuyExchangeRateValueTest extends ExchangeRateValueTestCase
{
    protected function createInstance(float $rate): BuyExchangeRateValue
    {
        return new BuyExchangeRateValue($rate);
    }

    public function testEqualsFalseForOtherInstances(): void
    {
        self::assertFalse(
            (new SellExchangeRateValue(1.0))->isEqualTo($this->createInstance(1.0)),
        );
    }
}
