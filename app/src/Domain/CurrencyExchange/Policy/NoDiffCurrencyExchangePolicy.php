<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Money;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\MoneyCurrencyExchange;

final readonly class NoDiffCurrencyExchangePolicy implements CurrencyExchangePolicyInterface
{
    public function calculateFinalExchange(MoneyCurrencyExchange $currencyExchange): Money
    {
        return new Money(
            $currencyExchange->toMoney->currency,
            $currencyExchange->toMoney->amount,
        );
    }
}
