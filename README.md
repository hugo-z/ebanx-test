# Ebanx-test
An Ebanx test which demonstrates a simple api 

### `GET` Balance
```php
/balance?account_id=100
```
### `POST` Reset
```php
POST /reset
```

### `POST` Event
```php
# Deposit & Create a new account
{
  "type": "deposit",
  "destination": "100", // id of the account
  "amount": "10"
}

# Withdraw
{
  "type": "withdraw",
  "destination": "100", // id of the account
  "amount": "10"
}

# Transfer
{
  "type": "transfer",
  "origin": "100", // id of the account
  "destination": "300", // id of the account
  "amount": "10"
}
```
---
#### `GET` All Accounts
```php
/getAllAccounts
```
