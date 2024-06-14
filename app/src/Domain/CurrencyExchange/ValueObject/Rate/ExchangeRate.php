<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;

abstract class ExchangeRate
{
    public const INVERT_SCALE = 5;
    public const PROVIDE_SCALE = 4;
    public const OPERATION_ROUNDING_MODE = RoundingMode::HALF_UP;

    final private function __construct(
        public readonly Currency $fromCurrency,
        public readonly Currency $toCurrency,
        public readonly float $rate,
    ) {
    }

    public function invert(): static
    {
        return new static(
            $this->toCurrency,
            $this->fromCurrency,
            self::calculateInvertedScaledRate($this->rate)->toFloat(),
        );
    }

    public static function asBuyRate(Currency $buyCurrency, Currency $sellCurrency, float $rate): BuyRate
    {
        return new BuyRate($buyCurrency, $sellCurrency, self::scaleRate($rate)->toFloat());
    }

    public static function asSellRate(Currency $sellCurrency, Currency $buyCurrency, float $rate): SellRate
    {
        return new SellRate($sellCurrency, $buyCurrency, self::scaleRate($rate)->toFloat());
    }

    private static function scaleRate(float $rate): BigDecimal
    {
        return BigDecimal::of($rate)
            ->toScale(self::PROVIDE_SCALE, self::OPERATION_ROUNDING_MODE);
    }

    private static function calculateInvertedScaledRate(float $rate): BigDecimal
    {
        return BigDecimal::of(1)
            ->dividedBy($rate, self::INVERT_SCALE, self::OPERATION_ROUNDING_MODE);
    }
}
