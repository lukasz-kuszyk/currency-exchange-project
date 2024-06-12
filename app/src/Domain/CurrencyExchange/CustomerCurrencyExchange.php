<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\BuyCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\SellCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\CurrencyExchangePolicyInterface;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\BuyCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\SellCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
use Webmozart\Assert\Assert;

readonly class CustomerCurrencyExchange
{
    public function sell(
        SellCurrency $sellCurrency,
        CurrencyExchangePolicyInterface $currencyExchangePolicy,
    ): CurrencyAmount {
        $rate = $sellCurrency->exchangeRateValue;
        $quote = null;

        if ($rate instanceof BuyExchangeRateValue) {
            $quote = SellCurrencyQuota::withBuyRate(
                $sellCurrency->fromCurrency,
                $sellCurrency->toCurrency,
                $rate,
                $sellCurrency->amount,
            );
        }

        if ($rate instanceof SellExchangeRateValue) {
            $quote = SellCurrencyQuota::withSellRate(
                $sellCurrency->fromCurrency,
                $sellCurrency->toCurrency,
                $rate,
                $sellCurrency->amount,
            );
        }

        Assert::notNull($quote);

        return $currencyExchangePolicy->calculateFinalExchange($quote);
    }

    public function buy(
        BuyCurrency $buyCurrency,
        CurrencyExchangePolicyInterface $currencyExchangePolicy,
    ): CurrencyAmount {
        $rate = $buyCurrency->exchangeRateValue;
        $quote = null;

        if ($rate instanceof BuyExchangeRateValue) {
            $quote = BuyCurrencyQuota::withBuyRate(
                $buyCurrency->fromCurrency,
                $buyCurrency->toCurrency,
                $rate,
                $buyCurrency->amount,
            );
        }

        if ($rate instanceof SellExchangeRateValue) {
            $quote = BuyCurrencyQuota::withSellRate(
                $buyCurrency->fromCurrency,
                $buyCurrency->toCurrency,
                $rate,
                $buyCurrency->amount,
            );
        }

        Assert::notNull($quote);

        return $currencyExchangePolicy->calculateFinalExchange($quote);
    }
}
