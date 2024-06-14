<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\CustomerCurrencyExchange;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\CustomerBuyCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\CustomerSellCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\MarginCustomerCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\NoDiffCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Money;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyRate;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellRate;
use PHPUnit\Framework\TestCase;

class CustomerCurrencyExchangeTest extends TestCase
{
    public function testCustomerSellCurrencyAtoCurrencyBWithNoDiffPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientSell = new CustomerSellCurrency(
            new Money($currencyEUR, 100.0),
            BuyRate::asBuyRate($currencyEUR, $currencyGBP, 1.5678),
        );
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->sell($clientSell, $policy);

        // then
        $expected = (new Money($currencyGBP, 156.78));
        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testCustomerSellCurrencyAtoCurrencyBWithMarginPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientSell = new CustomerSellCurrency(
            new Money($currencyEUR, 100.0),
            BuyRate::asBuyRate($currencyEUR, $currencyGBP, 1.5678),
        );
        $policy = new MarginCustomerCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->sell($clientSell, $policy);

        // then
        $expected = (new Money($currencyGBP, 158.35));
        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testCustomerBuyCurrencyAtoCurrencyBWithNoDiffPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientBuy = new CustomerBuyCurrency(
            new Money($currencyGBP, 100.0),
            SellRate::asSellRate($currencyEUR, $currencyGBP, 1.5678)->invert(),
        );
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->buy($clientBuy, $policy);

        // then
        $expected = (new Money($currencyEUR, 63.78));
        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testCustomerBuyCurrencyAtoCurrencyBWithMarginPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientBuy = new CustomerBuyCurrency(
            new Money($currencyGBP, 100.0),
            SellRate::asSellRate($currencyEUR, $currencyGBP, 1.5678)->invert(),
        );
        $policy = new MarginCustomerCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->buy($clientBuy, $policy);

        // then
        $expected = (new Money($currencyEUR, 63.14));
        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testCustomerSellCurrencyBtoCurrencyAWithNoDiffPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientSell = new CustomerSellCurrency(
            new Money($currencyGBP, 100.0),
            BuyRate::asBuyRate($currencyGBP, $currencyEUR, 1.5432),
        );
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->sell($clientSell, $policy);

        // then
        $expected = (new Money($currencyEUR, 154.32));
        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testCustomerSellCurrencyBtoCurrencyAWithMarginPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientSell = new CustomerSellCurrency(
            new Money($currencyGBP, 100.0),
            BuyRate::asBuyRate($currencyGBP, $currencyEUR, 1.5432),
        );
        $policy = new MarginCustomerCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->sell($clientSell, $policy);

        // then
        $expected = (new Money($currencyEUR, 155.86));
        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testCustomerBuyCurrencyBtoCurrencyAWithNoDiffPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientBuy = new CustomerBuyCurrency(
            new Money($currencyEUR, 100.0),
            SellRate::asSellRate($currencyGBP, $currencyEUR, 1.5432)->invert(),
        );
        $policy = new NoDiffCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->buy($clientBuy, $policy);

        // then
        $expected = (new Money($currencyGBP, 64.80));
        self::assertTrue($expected->isEqualTo($actual));
    }

    public function testCustomerBuyCurrencyBtoCurrencyAWithMarginPolicy(): void
    {
        // given
        $currencyEUR = new Currency('EUR');
        $currencyGBP = new Currency('GBP');

        $clientBuy = new CustomerBuyCurrency(
            new Money($currencyEUR, 100.0),
            SellRate::asSellRate($currencyGBP, $currencyEUR, 1.5432)->invert(),
        );
        $policy = new MarginCustomerCurrencyExchangePolicy();

        // when
        $actual = (new CustomerCurrencyExchange())->buy($clientBuy, $policy);

        // then
        $expected = (new Money($currencyGBP, 64.15));
        self::assertTrue($expected->isEqualTo($actual));
    }
}
