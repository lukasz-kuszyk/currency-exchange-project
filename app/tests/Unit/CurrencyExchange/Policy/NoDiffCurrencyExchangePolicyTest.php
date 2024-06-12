<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\NoDiffCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\ExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use PHPUnit\Framework\TestCase;

class NoDiffCurrencyExchangePolicyTest extends TestCase
{
    public function testBuyNoDiffCurrencyExchangePolicy(): void
    {
        $currencyA = new Currency('A');
        $currencyAAmount = new CurrencyAmount($currencyA, 100);

        $currencyB = new Currency('B');
        $currencyBAmount = new CurrencyAmount($currencyB, 200);

        $exchangeAmount = new ExchangeCurrencyAmount(
            $currencyAAmount,
            $currencyBAmount,
            new BuyExchangeRateValue(2.0),
        );

        $policy = new NoDiffCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($exchangeAmount);

        self::assertTrue(
            $actual->getBeforeExchangedCurrencyAmount()->isEqualTo($exchangeAmount),
        );

        self::assertTrue(
            $actual->getFinalCurrencyAmount()->isEqualTo($currencyBAmount),
        );

        self::assertTrue(
            $actual->getDifferenceCurrencyAmount()->isEqualTo(
                new CurrencyAmount($currencyB, .0),
            )
        );
    }

    public function testSellNoDiffCurrencyExchangePolicy(): void
    {
        $currencyA = new Currency('A');
        $currencyAAmount = new CurrencyAmount($currencyA, 200);

        $currencyB = new Currency('B');
        $currencyBAmount = new CurrencyAmount($currencyB, 100);

        $exchangeAmount = new ExchangeCurrencyAmount(
            $currencyAAmount,
            $currencyBAmount,
            new SellExchangeRateValue(2.0),
        );

        $policy = new NoDiffCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($exchangeAmount);

        self::assertTrue(
            $actual->getBeforeExchangedCurrencyAmount()->isEqualTo($exchangeAmount),
        );

        self::assertTrue(
            $actual->getFinalCurrencyAmount()->isEqualTo($currencyBAmount),
        );

        self::assertTrue(
            $actual->getDifferenceCurrencyAmount()->isEqualTo(
                new CurrencyAmount($currencyB, .0),
            )
        );
    }
}
