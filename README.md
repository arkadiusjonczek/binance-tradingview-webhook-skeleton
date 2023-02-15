# php-tradingview-binance-webhook-skeleton

A minimum viable webhook for tradingview alerts to trade on binance written in php.

## Requirements

1. Webhosting for php webhook (e.g. uberspace.de if you are located in germany)
2. Binace API and secret key (get webhosting first because you need to add IP address of webserver to unlock trading for the API key)
3. TradingView alerts (you can create a strategy, backtest it and create an alert which calls the php webhook :))

## TradingView Alert

Add the following message to your alert and set webhook url in the notifcations.

```
{{ticker}},{{strategy.order.action}},{{strategy.order.price}}
```

The {{strategy.order.price}} is optional but you can use it later to compare price of strategy with price of fulfilled order.
