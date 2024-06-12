<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Quota\CurrencyQuota;

final readonly class NoDiffCurrencyExchangePolicy implements CurrencyExchangePolicyInterface
{
    public function calculateFinalExchange(CurrencyQuota $quota): CurrencyAmount
    {
        return new CurrencyAmount(
            $quota->getExchangeCurrencyAmount()->getCurrency(),
            $quota->getExchangeCurrencyAmount()->getAmount(),
        );
    }
}
