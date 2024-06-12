<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\CustomerCurrencyExchange;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\BuyCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\SellCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\NoDiffCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use PHPUnit\Framework\TestCase;

class CustomerCurrencyExchangeTest extends TestCase
{
    public function testValidSellWithSellRate(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');
        $EURtoGBP = new SellExchangeRateValue(1.5678);

        $sellCurrency = new SellCurrency($currencyEUR, $currencyGBP, $EURtoGBP, 100.0);
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $exchange = new CustomerCurrencyExchange();
        $actual = $exchange->sell($sellCurrency, $policy);

        // then
        $expected = new CurrencyAmount($currencyGBP, 156.78);

        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testValidSellWithBuyRate(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');
        $BGPtoEUR = new BuyExchangeRateValue(1.5432);

        $sellCurrency = new SellCurrency($currencyGBP, $currencyEUR, $BGPtoEUR, 100.0);
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $exchange = new CustomerCurrencyExchange();
        $actual = $exchange->sell($sellCurrency, $policy);

        // then
        $expected = new CurrencyAmount($currencyEUR, 154.32);

        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testValidBuyWithSellRate(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');
        $EURtoGBP = new SellExchangeRateValue(1.5678);

        $buyCurrency = new BuyCurrency($currencyEUR, $currencyGBP, $EURtoGBP, 100.0);
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $exchange = new CustomerCurrencyExchange();
        $actual = $exchange->buy($buyCurrency, $policy);

        // then
        $expected = new CurrencyAmount($currencyEUR, 63.78);

        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testValidBuyWithBuyRate(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');
        $BGPtoEUR = new BuyExchangeRateValue(1.5432);

        $buyCurrency = new BuyCurrency($currencyGBP, $currencyEUR, $BGPtoEUR, 100.0);
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $exchange = new CustomerCurrencyExchange();
        $actual = $exchange->buy($buyCurrency, $policy);

        // then
        $expected = new CurrencyAmount($currencyGBP, 64.8);

        self::assertTrue($expected->isEqualTo($actual));
    }
}
