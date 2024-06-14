<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Money;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\MoneyCurrencyExchange;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellRate;

final readonly class MarginCustomerCurrencyExchangePolicy implements CurrencyExchangePolicyInterface
{
    public const PERCENT_VALUE = 0.01;
    public const SCALE_VALUE = 2;
    public const ROUNDING_MODE = RoundingMode::HALF_UP;

    public function calculateFinalExchange(MoneyCurrencyExchange $currencyExchange): Money
    {
        $marginAmount = BigDecimal::of($currencyExchange->toMoney->amount)
            ->multipliedBy(self::PERCENT_VALUE);

        if ($currencyExchange->rate instanceof SellRate) {
            $marginAmount = $marginAmount->multipliedBy(-1); // for subtraction
        }

        $finalAmount = BigDecimal::of($currencyExchange->toMoney->amount)
            ->plus($marginAmount)
            ->toScale(self::SCALE_VALUE, self::ROUNDING_MODE)
            ->toFloat();

        return new Money(
            $currencyExchange->toMoney->currency,
            $finalAmount,
        );
    }
}
