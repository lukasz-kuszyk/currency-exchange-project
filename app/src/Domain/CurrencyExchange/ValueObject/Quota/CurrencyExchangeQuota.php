<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\ExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;

readonly class CurrencyExchangeQuota
{
    public function __construct(
        private Currency $fromCurrency,
        private Currency $toCurrency,
        private BuyExchangeRateValue $buyExchangeRateValue,
        private SellExchangeRateValue $sellExchangeRateValue,
    ) {
    }

    public function sell(CurrencyAmount $currencyAmount): ExchangeCurrencyAmount
    {
        $isFromCurrency = $this->isFromCurrency($currencyAmount);

        $destCurrency = $this->getDestinationCurrency($isFromCurrency);
        $rate = $this->getDestinationRate($isFromCurrency);

        $amount = BigDecimal::of($currencyAmount->getAmount())
            ->multipliedBy($rate->getRate())
            ->toFloat();

        return new ExchangeCurrencyAmount(
            $currencyAmount,
            new CurrencyAmount($destCurrency, $amount),
            $rate
        );
    }

    public function buy(CurrencyAmount $currencyAmount): ExchangeCurrencyAmount
    {
        $isFromCurrency = $this->isFromCurrency($currencyAmount);

        $destCurrency = $this->getDestinationCurrency($isFromCurrency);
        $rate = $this->getDestinationRate($isFromCurrency);

        $amount = BigDecimal::of($currencyAmount->getAmount())
            ->dividedBy($rate->getRate(), 2, RoundingMode::HALF_UP)
            ->toFloat();

        return new ExchangeCurrencyAmount(
            $currencyAmount,
            new CurrencyAmount($destCurrency, $amount),
            $rate
        );
    }

    private function isFromCurrency(CurrencyAmount $currencyAmount): bool
    {
        return $currencyAmount->getCurrency()->isEqualTo($this->fromCurrency);
    }

    private function getDestinationCurrency(bool $isFromCurrency): Currency
    {
        return $isFromCurrency ? $this->toCurrency : $this->fromCurrency;
    }

    private function getDestinationRate(bool $isFromCurrency): ExchangeRateValue
    {
        return $isFromCurrency ? $this->sellExchangeRateValue : $this->buyExchangeRateValue;
    }
}
