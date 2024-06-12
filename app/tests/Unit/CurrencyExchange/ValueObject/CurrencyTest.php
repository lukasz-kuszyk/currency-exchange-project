<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\ValueObject;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Exception\CurrencyEmptyCodeException;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use PHPUnit\Framework\TestCase;

final class CurrencyTest extends TestCase
{
    public function testEqualsTrue(): void
    {
        $actual = (new Currency('PLN'))->isEqualTo(new Currency('PLN'));

        self::assertTrue($actual);
    }

    public function testEqualsFalse(): void
    {
        $actual = (new Currency('PLN'))->isEqualTo(new Currency('USD'));

        self::assertFalse($actual);
    }

    public function testThrowsExceptionOnEmptyCode(): void
    {
        self::expectException(CurrencyEmptyCodeException::class);

        new Currency('');
    }
}
