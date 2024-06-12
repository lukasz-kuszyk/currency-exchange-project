@withoutFee
Feature: Currency Exchange without Fee

  Scenario: Client sells 100 EUR for GBP
    Given the exchange rate from EUR to GBP is 1.5678
    When the client sells 100 EUR
    Then the client should receive 156.78 GBP

  Scenario: Client buys 100 GBP with EUR
    Given the exchange rate from EUR to GBP is 1.5678
    When the client buys 100 GBP
    Then the client should pay 63.78 EUR

  Scenario: Client sells 100 GBP for EUR
    Given the exchange rate from GBP to EUR is 1.5432
    When the client sells 100 GBP
    Then the client should receive 154.32 EUR

  Scenario: Client buys 100 EUR with GBP
    Given the exchange rate from GBP to EUR is 1.5432
    When the client buys 100 EUR
    Then the client should pay 64.80 GBP
