<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\NoDiffCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\BuyCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\SellCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use PHPUnit\Framework\TestCase;

class NoDiffCurrencyExchangePolicyTest extends TestCase
{
    public function testSellCurrencyExchangePolicy(): void
    {
        // given
        $quota = SellCurrencyQuota::withSellRate(
            new Currency('A'),
            new Currency('B'),
            new SellExchangeRateValue(2.0),
            5.00
        );

        // when
        $policy = new NoDiffCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($quota);

        // then
        self::assertTrue(
            $quota->getExchangeCurrencyAmount()->isEqualTo($actual)
        );
    }

    public function testBuyCurrencyExchangePolicy(): void
    {
        // given
        $quota = BuyCurrencyQuota::withBuyRate(
            new Currency('A'),
            new Currency('B'),
            new BuyExchangeRateValue(2.0),
            5.00
        );

        // when
        $policy = new NoDiffCurrencyExchangePolicy();
        $actual = $policy->calculateFinalExchange($quota);

        // then
        self::assertTrue(
            $quota->getExchangeCurrencyAmount()->isEqualTo($actual)
        );
    }
}
