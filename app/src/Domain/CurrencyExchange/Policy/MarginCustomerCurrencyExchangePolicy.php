<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\BuyCurrencyQuota;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\CurrencyQuota;

final readonly class MarginCustomerCurrencyExchangePolicy implements CurrencyExchangePolicyInterface
{
    public const PERCENT_VALUE = 0.01;
    public const SCALE_VALUE = 2;
    public const ROUNDING_MODE = RoundingMode::HALF_UP;

    public function calculateFinalExchange(CurrencyQuota $quota): CurrencyAmount
    {
        $marginAmount = BigDecimal::of($quota->getExchangeCurrencyAmount()->getAmount())
            ->multipliedBy(self::PERCENT_VALUE);

        if ($quota instanceof BuyCurrencyQuota) {
            $marginAmount = $marginAmount->multipliedBy(-1); // for subtraction
        }

        $finalAmount = BigDecimal::of($quota->getExchangeCurrencyAmount()->getAmount())
            ->plus($marginAmount)
            ->toScale(self::SCALE_VALUE, self::ROUNDING_MODE)
            ->toFloat();

        return new CurrencyAmount(
            $quota->getExchangeCurrencyAmount()->getCurrency(),
            $finalAmount,
        );
    }
}
