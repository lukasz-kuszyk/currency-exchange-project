<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Money;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyRate;

readonly class CustomerSellCurrency
{
    public function __construct(
        public Money $customerMoney,
        public BuyRate $buyRate,
    ) {
    }
}
