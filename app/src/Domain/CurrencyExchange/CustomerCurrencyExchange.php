<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\CustomerBuyCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\CustomerSellCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\CurrencyExchangePolicyInterface;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Money;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\MoneyCurrencyExchange;

readonly class CustomerCurrencyExchange
{
    public function sell(
        CustomerSellCurrency $sellCurrency,
        CurrencyExchangePolicyInterface $currencyExchangePolicy,
    ): Money {
        $exchange = MoneyCurrencyExchange::fromBuyOperation(
            $sellCurrency->customerMoney,
            $sellCurrency->buyRate,
        );

        return $currencyExchangePolicy->calculateFinalExchange($exchange);
    }

    public function buy(
        CustomerBuyCurrency $buyCurrency,
        CurrencyExchangePolicyInterface $currencyExchangePolicy,
    ): Money {
        $exchange = MoneyCurrencyExchange::fromSellOperation(
            $buyCurrency->customerMoney,
            $buyCurrency->sellRate,
        );

        return $currencyExchangePolicy->calculateFinalExchange($exchange);
    }
}
