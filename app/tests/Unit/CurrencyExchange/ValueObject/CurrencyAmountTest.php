<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\ValueObject;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use PHPUnit\Framework\TestCase;

final class CurrencyAmountTest extends TestCase
{
    public function testEqualsTrue(): void
    {
        $currencyAmount1 = new CurrencyAmount(new Currency('A'), 1.0);
        $currencyAmount2 = new CurrencyAmount(new Currency('A'), 1.0);

        self::assertTrue($currencyAmount1->isEqualTo($currencyAmount2));
    }

    public function testEqualsFalse(): void
    {
        $currencyAmount1 = new CurrencyAmount(new Currency('A'), 1.0);
        $currencyAmount2 = new CurrencyAmount(new Currency('A'), 0.0);

        self::assertFalse($currencyAmount1->isEqualTo($currencyAmount2));
    }
}
