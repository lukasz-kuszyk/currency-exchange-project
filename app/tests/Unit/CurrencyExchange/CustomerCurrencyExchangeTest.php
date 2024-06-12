<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Unit\CurrencyExchange;

use Brick\Math\BigDecimal;
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
    public function testValidSell(): void
    {
        $exchange = new CustomerCurrencyExchange();

        $result = $exchange->sell(
            new SellCurrency(
                new Currency('A'),
                new CurrencyAmount(new Currency('B'), 100.0),
                new BuyExchangeRateValue(1.5432),
                new SellExchangeRateValue(1.5678),
            ),
            new NoDiffCurrencyExchangePolicy(),
        );

        self::assertTrue(
            BigDecimal::of($result->getFinalCurrencyAmount()->getAmount())->isEqualTo(156.78),
        );
    }

    public function testValidBuy(): void
    {
        $exchange = new CustomerCurrencyExchange();

        $result = $exchange->buy(
            new BuyCurrency(
                new Currency('A'),
                new CurrencyAmount(new Currency('B'), 100.0),
                new BuyExchangeRateValue(1.5432),
                new SellExchangeRateValue(1.5678),
            ),
            new NoDiffCurrencyExchangePolicy(),
        );

        self::assertTrue(
            BigDecimal::of($result->getFinalCurrencyAmount()->getAmount())->isEqualTo(63.78),
        );
    }
}
