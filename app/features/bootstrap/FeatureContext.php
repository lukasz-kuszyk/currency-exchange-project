<?php

use Behat\Behat\Context\Context;
use Brick\Math\BigDecimal;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\CustomerCurrencyExchange;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\BuyCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Model\SellCurrency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\CurrencyExchangePolicyInterface;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\MarginCustomerCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\Policy\NoDiffCurrencyExchangePolicy;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Currency;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\CurrencyAmount;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\BuyExchangeRateValue;
use Nauta\CurrencyExchangeProject\Domain\CurrencyExchange\ValueObject\Rate\SellExchangeRateValue;
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
    private CurrencyAmount $amountReceived;

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
    public function theExchangeRateFromEurToGbpIs($rate): void
    {
        $this->exchangeRate = BigDecimal::of($rate);
    }

    /**
     * @Given the exchange rate from GBP to EUR is :rate
     */
    public function theExchangeRateFromGbpToEurIs($rate): void
    {
        $this->exchangeRate = BigDecimal::of($rate);
    }

    /**
     * @When the client sells :amount EUR
     */
    public function theClientSellsEur($amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $sellCurrency = new SellCurrency(
            $this->currencyEUR,
            $this->currencyGBP,
            new SellExchangeRateValue($this->exchangeRate->toFloat()),
            $this->amountGiven->toFloat(),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->amountReceived = $exchange->sell($sellCurrency, $policy);
    }

    /**
     * @When the client buys :amount GBP
     */
    public function theClientBuysGbp($amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $buyCurrency = new BuyCurrency(
            $this->currencyEUR,
            $this->currencyGBP,
            new SellExchangeRateValue($this->exchangeRate->toFloat()),
            $this->amountGiven->toFloat(),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->amountReceived = $exchange->buy($buyCurrency, $policy);
    }

    /**
     * @When the client sells :amount GBP
     */
    public function theClientSellsGbp($amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $sellCurrency = new SellCurrency(
            $this->currencyEUR,
            $this->currencyGBP,
            new SellExchangeRateValue($this->exchangeRate->toFloat()),
            $this->amountGiven->toFloat(),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->amountReceived = $exchange->sell($sellCurrency, $policy);
    }

    /**
     * @When the client buys :amount EUR
     */
    public function theClientBuysEur($amount): void
    {
        $this->amountGiven = BigDecimal::of($amount);

        $buyCurrency = new BuyCurrency(
            $this->currencyEUR,
            $this->currencyGBP,
            new BuyExchangeRateValue($this->exchangeRate->toFloat()),
            $this->amountGiven->toFloat(),
        );
        $policy = $this->createPolicy();

        $exchange = new CustomerCurrencyExchange();
        $this->amountReceived = $exchange->buy($buyCurrency, $policy);
    }

    /**
     * @Then the client should receive :amount GBP
     */
    public function theClientShouldReceiveGbp($amount): void
    {
        Assert::assertTrue(
            BigDecimal::of($amount)->isEqualTo($this->amountReceived->getAmount())
        );
    }

    /**
     * @Then the client should pay :amount EUR
     */
    public function theClientShouldPayEur($amount): void
    {
        Assert::assertTrue(
            BigDecimal::of($amount)->isEqualTo($this->amountReceived->getAmount())
        );
    }

    /**
     * @Then the client should receive :amount EUR
     */
    public function theClientShouldReceiveEur($amount): void
    {
        Assert::assertTrue(
            BigDecimal::of($amount)->isEqualTo($this->amountReceived->getAmount())
        );
    }

    /**
     * @Then the client should pay :amount GBP
     */
    public function theClientShouldPayGbp($amount): void
    {
        Assert::assertTrue(
            BigDecimal::of($amount)->isEqualTo($this->amountReceived->getAmount())
        );
    }

    protected function createPolicy(): CurrencyExchangePolicyInterface
    {
        return $this->applyFee ? new MarginCustomerCurrencyExchangePolicy() : new NoDiffCurrencyExchangePolicy();
    }
}
