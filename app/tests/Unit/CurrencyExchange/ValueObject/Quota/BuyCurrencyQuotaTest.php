<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange\ValueObject\Quota;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\BuyCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use PHPUnit\Framework\TestCase;

final class BuyCurrencyQuotaTest extends TestCase
{
    public function testCreateWithSellRate(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $EURtoGBP = new SellExchangeRateValue(1.5678);

        // when
        $quota = BuyCurrencyQuota::withSellRate($currencyEUR, $currencyGBP, $EURtoGBP, 100.0);

        // then
        self::assertTrue(
            $quota->getExchangeCurrencyAmount()->isEqualTo(
                new CurrencyAmount($currencyEUR, 63.78)
            ),
        );
    }

    public function testCreateWithBuyRate(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $BGPtoEUR = new BuyExchangeRateValue(1.5432);

        // when
        $quota = BuyCurrencyQuota::withBuyRate($currencyGBP, $currencyEUR, $BGPtoEUR, 100.0);

        // then
        self::assertTrue(
            $quota->getExchangeCurrencyAmount()->isEqualTo(
                new CurrencyAmount($currencyGBP, 64.8)
            )
        );
    }
}
