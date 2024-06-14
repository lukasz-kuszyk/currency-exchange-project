<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Exception\InvalidCurrencyExchangeOperationException;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyRate;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\ExchangeRate;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellRate;

class MoneyCurrencyExchange
{
    public const OPERATION_SCALE = 2;
    public const OPERATION_ROUNDING_MODE = RoundingMode::HALF_UP;

    private function __construct(
        public readonly Money $fromMoney,
        public readonly Money $toMoney,
        public readonly ExchangeRate $rate,
    ) {
    }

    public static function fromBuyOperation(Money $money, BuyRate $rate): MoneyCurrencyExchange
    {
        if (!$money->currency->isEqualTo($rate->fromCurrency)) {
            throw new InvalidCurrencyExchangeOperationException();
        }

        $amount = BigDecimal::of($money->amount)
            ->multipliedBy($rate->rate)
            ->toScale(self::OPERATION_SCALE, self::OPERATION_ROUNDING_MODE);

        return new MoneyCurrencyExchange(
            $money,
            new Money($rate->toCurrency, $amount->toFloat()),
            $rate,
        );
    }

    public static function fromSellOperation(Money $money, SellRate $rate): MoneyCurrencyExchange
    {
        if (!$money->currency->isEqualTo($rate->fromCurrency)) {
            throw new InvalidCurrencyExchangeOperationException();
        }

        $amount = BigDecimal::of($money->amount)
            ->multipliedBy($rate->rate)
            ->toScale(self::OPERATION_SCALE, self::OPERATION_ROUNDING_MODE);

        return new MoneyCurrencyExchange(
            $money,
            new Money($rate->toCurrency, $amount->toFloat()),
            $rate,
        );
    }
}
