<?php

declare(strict_types=1);

namespace Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy;

use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\ExchangeCurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\FinalExchangeCurrencyAmount;

final readonly class NoDiffCurrencyExchangePolicy implements CurrencyExchangePolicyInterface
{
    public function calculateFinalExchange(ExchangeCurrencyAmount $exchangedCurrencyAmount): FinalExchangeCurrencyAmount
    {
        return new FinalExchangeCurrencyAmount(
            $exchangedCurrencyAmount,
            new CurrencyAmount($exchangedCurrencyAmount->getToCurrencyAmount()->getCurrency(), .0),
            $exchangedCurrencyAmount->getToCurrencyAmount(),
        );
    }
}
