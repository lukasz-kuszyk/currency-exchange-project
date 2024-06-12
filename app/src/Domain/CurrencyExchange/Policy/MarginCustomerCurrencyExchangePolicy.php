<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\ExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\FinalExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;

final readonly class MarginCustomerCurrencyExchangePolicy implements CurrencyExchangePolicyInterface
{
    public const PERCENT_VALUE = 0.01;

    public function calculateFinalExchange(
        ExchangeCurrencyAmount $exchangedCurrencyAmount,
    ): FinalExchangeCurrencyAmount {
        $marginAmount = BigDecimal::of($exchangedCurrencyAmount->getToCurrencyAmount()->getAmount())
            ->multipliedBy(self::PERCENT_VALUE);

        if ($exchangedCurrencyAmount->getExchangeRate() instanceof BuyExchangeRateValue) {
            $marginAmount = $marginAmount->multipliedBy(-1);
        }

        $finalAmount = BigDecimal::of($exchangedCurrencyAmount->getToCurrencyAmount()->getAmount())
            ->plus($marginAmount)
            ->toScale(2, RoundingMode::HALF_UP)
            ->toFloat();

        $destCurrency = $exchangedCurrencyAmount->getToCurrencyAmount()->getCurrency();

        return new FinalExchangeCurrencyAmount(
            $exchangedCurrencyAmount,
            new CurrencyAmount($destCurrency, $marginAmount->toFloat()),
            new CurrencyAmount($destCurrency, $finalAmount),
        );
    }
}
