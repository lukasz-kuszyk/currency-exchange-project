<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\MarginCustomerCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\BuyCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\SellCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use PHPUnit\Framework\TestCase;

class MarginCustomerCurrencyExchangePolicyTest extends TestCase
{
    public function testSellCurrencyExchangePolicy(): void
    {
        // given
        $quota = SellCurrencyQuota::withSellRate(
            new Currency('A'),
            new Currency('B'),
            new SellExchangeRateValue(1.5678),
            100.0
        );

        // when
        $policy = new MarginCustomerCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($quota);

        // then
        $expected = new CurrencyAmount(
            $quota->getExchangeCurrencyAmount()->getCurrency(),
            158.35
        );

        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testBuyCurrencyExchangePolicy(): void
    {
        // given
        $quota = BuyCurrencyQuota::withBuyRate(
            new Currency('A'),
            new Currency('B'),
            new BuyExchangeRateValue(1.5432),
            100.0
        );

        // when
        $policy = new MarginCustomerCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($quota);

        // then
        $expected = new CurrencyAmount(
            $quota->getExchangeCurrencyAmount()->getCurrency(),
            64.15
        );

        self::assertTrue($expected->isEqualTo($actual));
    }
}
