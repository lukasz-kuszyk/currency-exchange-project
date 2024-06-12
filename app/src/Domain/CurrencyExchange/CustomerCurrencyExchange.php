<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\BuyCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\SellCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\CurrencyExchangePolicyInterface;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\FinalExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\CurrencyExchangeQuota;

readonly class CustomerCurrencyExchange
{
    public function sell(
        SellCurrency $sellCurrency,
        CurrencyExchangePolicyInterface $currencyExchangePolicy,
    ): FinalExchangeCurrencyAmount {
        $quota = new CurrencyExchangeQuota(
            $sellCurrency->sellCurrency,
            $sellCurrency->buyCurrencyAmount->getCurrency(),
            $sellCurrency->buyExchangeRateValue,
            $sellCurrency->sellExchangeRateValue,
        );

        $exchangeAmountBefore = $quota->sell(
            new CurrencyAmount(
                $sellCurrency->sellCurrency,
                $sellCurrency->buyCurrencyAmount->getAmount(),
            )
        );

        return $currencyExchangePolicy->calculateFinalExchange($exchangeAmountBefore);
    }

    public function buy(
        BuyCurrency $buyCurrency,
        CurrencyExchangePolicyInterface $currencyExchangePolicy,
    ): FinalExchangeCurrencyAmount {
        $quota = new CurrencyExchangeQuota(
            $buyCurrency->buyCurrency,
            $buyCurrency->sellCurrencyAmount->getCurrency(),
            $buyCurrency->buyExchangeRateValue,
            $buyCurrency->sellExchangeRateValue,
        );

        $exchangeAmountBefore = $quota->buy(
            new CurrencyAmount(
                $buyCurrency->buyCurrency,
                $buyCurrency->sellCurrencyAmount->getAmount(),
            )
        );

        return $currencyExchangePolicy->calculateFinalExchange($exchangeAmountBefore);
    }
}
