<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Money;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\MoneyCurrencyExchange;

interface CurrencyExchangePolicyInterface
{
    public function calculateFinalExchange(MoneyCurrencyExchange $currencyExchange): Money;
}
