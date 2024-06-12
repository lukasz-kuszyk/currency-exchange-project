<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\ExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\FinalExchangeCurrencyAmount;

interface CurrencyExchangePolicyInterface
{
    public function calculateFinalExchange(
        ExchangeCurrencyAmount $exchangedCurrencyAmount,
    ): FinalExchangeCurrencyAmount;
}
