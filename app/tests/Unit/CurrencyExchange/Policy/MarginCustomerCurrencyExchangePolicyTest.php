<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\Policy;

use Brick\Math\BigDecimal;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\MarginCustomerCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\CurrencyExchangeQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use PHPUnit\Framework\TestCase;

class MarginCustomerCurrencyExchangePolicyTest extends TestCase
{
    public function testSellCurrencyExchangePolicy(): void
    {
        $currencyA = new Currency('A');
        $currencyB = new Currency('B');

        $BtoARate = new BuyExchangeRateValue(1.5432);
        $AtoBRate = new SellExchangeRateValue(1.5678);

        $exchange = (new CurrencyExchangeQuota($currencyA, $currencyB, $BtoARate, $AtoBRate))
            ->sell(new CurrencyAmount($currencyA, 100.0));

        $policy = new MarginCustomerCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($exchange);

        self::assertTrue(
            $actual->getBeforeExchangedCurrencyAmount()->isEqualTo($exchange)
        );

        self::assertTrue(
            BigDecimal::of($actual->getDifferenceCurrencyAmount()->getAmount())->isEqualTo(1.5678)
        );

        self::assertTrue(
            BigDecimal::of($actual->getFinalCurrencyAmount()->getAmount())->isEqualTo(158.35),
        );
    }

    public function testBuyCurrencyExchangePolicy(): void
    {
        $currencyA = new Currency('A');
        $currencyB = new Currency('B');

        $BtoARate = new BuyExchangeRateValue(1.5432);
        $AtoBRate = new SellExchangeRateValue(1.5678);

        $exchange = (new CurrencyExchangeQuota($currencyA, $currencyB, $BtoARate, $AtoBRate))
            ->buy(new CurrencyAmount($currencyB, 100.0));

        $policy = new MarginCustomerCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($exchange);

        self::assertTrue(
            $actual->getBeforeExchangedCurrencyAmount()->isEqualTo($exchange)
        );

        self::assertTrue(
            BigDecimal::of($actual->getDifferenceCurrencyAmount()->getAmount())->isEqualTo(-0.648)
        );

        self::assertTrue(
            BigDecimal::of($actual->getFinalCurrencyAmount()->getAmount())->isEqualTo(64.15),
        );
    }
}
