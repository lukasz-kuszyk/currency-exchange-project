<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\ValueObject\Rate;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Exception\Rate\ExchangeRateIsZeroException;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;
use PHPUnit\Framework\TestCase;

abstract class ExchangeRateValueTestCase extends TestCase
{
    abstract protected function createInstance(float $rate): ExchangeRateValue;

    abstract public function testEqualsFalseForOtherInstances(): void;

    public function testThrowsExceptionOnZeroRate(): void
    {
        self::expectException(ExchangeRateIsZeroException::class);

        $this->createInstance(.0);
    }

    public function testEqualsTrue(): void
    {
        self::assertTrue(
            $this->createInstance(1.0)->isEqualTo($this->createInstance(1.0)),
        );
    }

    public function testEqualsFalse(): void
    {
        self::assertFalse(
            $this->createInstance(-1.0)->isEqualTo($this->createInstance(1.0)),
        );
    }
}
