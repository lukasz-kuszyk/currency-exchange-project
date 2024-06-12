<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\ValueObject\Quota;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\ExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\CurrencyExchangeQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use PHPUnit\Framework\TestCase;

final class CurrencyExchangeQuotaTest extends TestCase
{
    public function testValidSellFromCurrencyAtoB(): void
    {
        $currencyA = new Currency('A');
        $currencyB = new Currency('B');

        $BtoARate = new BuyExchangeRateValue(1.5432);
        $AtoBRate = new SellExchangeRateValue(1.5678);

        $actual = (new CurrencyExchangeQuota($currencyA, $currencyB, $BtoARate, $AtoBRate))
            ->sell(new CurrencyAmount($currencyA, 100.0));

        self::assertExpectedExchangedCurrencyAmount(
            $actual,
            $currencyA,
            100.0,
            $currencyB,
            156.78,
            $AtoBRate
        );
    }

    public function testValidSellFromCurrencyBtoA(): void
    {
        $currencyA = new Currency('A');
        $currencyB = new Currency('B');

        $BtoARate = new BuyExchangeRateValue(1.5432);
        $AtoBRate = new SellExchangeRateValue(1.5678);

        $actual = (new CurrencyExchangeQuota($currencyA, $currencyB, $BtoARate, $AtoBRate))
            ->sell(new CurrencyAmount($currencyB, 100.0));

        self::assertExpectedExchangedCurrencyAmount(
            $actual,
            $currencyB,
            100.0,
            $currencyA,
            154.32,
            $BtoARate
        );
    }

    public function testValidBuyFromCurrencyAtoB(): void
    {
        $currencyA = new Currency('A');
        $currencyB = new Currency('B');

        $BtoARate = new BuyExchangeRateValue(1.5432);
        $AtoBRate = new SellExchangeRateValue(1.5678);

        $actual = (new CurrencyExchangeQuota($currencyA, $currencyB, $BtoARate, $AtoBRate))
            ->buy(new CurrencyAmount($currencyA, 100.0));

        self::assertExpectedExchangedCurrencyAmount(
            $actual,
            $currencyA,
            100.0,
            $currencyB,
            63.78,
            $AtoBRate
        );
    }

    public function testValidBuyFromCurrencyBtoA(): void
    {
        $currencyA = new Currency('A');
        $currencyB = new Currency('B');

        $BtoARate = new BuyExchangeRateValue(1.5432);
        $AtoBRate = new SellExchangeRateValue(1.5678);

        $actual = (new CurrencyExchangeQuota($currencyA, $currencyB, $BtoARate, $AtoBRate))
            ->buy(new CurrencyAmount($currencyB, 100.0));

        self::assertExpectedExchangedCurrencyAmount(
            $actual,
            $currencyB,
            100.0,
            $currencyA,
            64.80,
            $BtoARate
        );
    }

    protected static function assertExpectedExchangedCurrencyAmount(
        ExchangeCurrencyAmount $actual,
        Currency $fromCurrency,
        float $fromCurrencyAmount,
        Currency $toCurrency,
        float $toCurrencyAmount,
        ExchangeRateValue $exchangeRateValue,
    ): void {
        self::assertTrue(
            $actual->getFromCurrencyAmount()->isEqualTo(new CurrencyAmount($fromCurrency, $fromCurrencyAmount)),
        );

        self::assertTrue(
            $actual->getToCurrencyAmount()->isEqualTo(new CurrencyAmount($toCurrency, $toCurrencyAmount)),
        );

        self::assertTrue(
            $exchangeRateValue->isEqualTo($actual->getExchangeRate()),
        );
    }
}
