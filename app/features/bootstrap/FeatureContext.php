<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Brick\Math\BigDecimal;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\CustomerCurrencyExchange;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\CustomerBuyCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\CustomerSellCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\CurrencyExchangePolicyInterface;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\MarginCustomerCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\NoDiffCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Money;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyRate;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellRate;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private bool $applyFee;
    private Currency $currencyEUR;
    private Currency $currencyGBP;
    private BigDecimal $exchangeRate;
    private BigDecimal $amountGiven;
    private Money $moneyReceived;

    public function __construct()
    {
        $this->currencyEUR = new Currency('EUR');
        $this->currencyGBP = new Currency('GBP');
    }

    /**
     * @BeforeScenario @withFee
     */
    public function enableFee(): void
    {
        $this->applyFee = true;
    }

    /**
     * @BeforeScenario @withoutFee
     */
    public function disableFee(): void
    {
        $this->applyFee = false;
    }

    /**
     * @Given the exchange rate from EUR to GBP is :rate
     */
    public function theExchangeRateFromEurToGbpIs(string $rate): void
    {
        $this->exchangeRate = BigDecimal::of($rate);
    }

    /**
     * @Given the exchange rate from GBP to EUR is :rate
     */
    public function theExchangeRateFromGbpToEurIs(string $rate): void
    {
        $this->exchangeRate = BigDecimal::of($rate);
    }

    /**
     * @When the client sells :amount EUR
     */
    public function theClientSellsEur(string $amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $clientSell = new CustomerSellCurrency(
            new Money($this->currencyEUR, $this->amountGiven->toFloat()),
            BuyRate::asBuyRate($this->currencyEUR, $this->currencyGBP, $this->exchangeRate->toFloat()),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->moneyReceived = $exchange->sell($clientSell, $policy);
    }

    /**
     * @When the client buys :amount GBP
     */
    public function theClientBuysGbp(string $amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $clientBuy = new CustomerBuyCurrency(
            new Money($this->currencyGBP, $this->amountGiven->toFloat()),
            SellRate::asSellRate($this->currencyEUR, $this->currencyGBP, $this->exchangeRate->toFloat())->invert(),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->moneyReceived = $exchange->buy($clientBuy, $policy);
    }

    /**
     * @When the client sells :amount GBP
     */
    public function theClientSellsGbp(string $amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $clientSell = new CustomerSellCurrency(
            new Money($this->currencyGBP, $this->amountGiven->toFloat()),
            BuyRate::asBuyRate($this->currencyGBP, $this->currencyEUR, $this->exchangeRate->toFloat()),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->moneyReceived = $exchange->sell($clientSell, $policy);
    }

    /**
     * @When the client buys :amount EUR
     */
    public function theClientBuysEur(string $amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $clientBuy = new CustomerBuyCurrency(
            new Money($this->currencyEUR, $this->amountGiven->toFloat()),
            SellRate::asSellRate($this->currencyGBP, $this->currencyEUR, $this->exchangeRate->toFloat())->invert(),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->moneyReceived = $exchange->buy($clientBuy, $policy);
    }

    /**
     * @Then the client should receive :amount GBP
     */
    public function theClientShouldReceiveGbp(string $amount): void
    {
        Assert::assertTrue(
            (new Money($this->currencyGBP, BigDecimal::of($amount)->toFloat()))->isEqualTo($this->moneyReceived)
        );
    }

    /**
     * @Then the client should pay :amount EUR
     */
    public function theClientShouldPayEur(string $amount): void
    {
        Assert::assertTrue(
            (new Money($this->currencyEUR, BigDecimal::of($amount)->toFloat()))->isEqualTo($this->moneyReceived)
        );
    }

    /**
     * @Then the client should receive :amount EUR
     */
    public function theClientShouldReceiveEur(string $amount): void
    {
        Assert::assertTrue(
            (new Money($this->currencyEUR, BigDecimal::of($amount)->toFloat()))->isEqualTo($this->moneyReceived)
        );
    }

    /**
     * @Then the client should pay :amount GBP
     */
    public function theClientShouldPayGbp(string $amount): void
    {
        Assert::assertTrue(
            (new Money($this->currencyGBP, BigDecimal::of($amount)->toFloat()))->isEqualTo($this->moneyReceived)
        );
    }

    protected function createPolicy(): CurrencyExchangePolicyInterface
    {
        return $this->applyFee ? new MarginCustomerCurrencyExchangePolicy() : new NoDiffCurrencyExchangePolicy();
    }
}
