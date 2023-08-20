# Order Management
This is a simple order management application. It allows to create orders with api.

Endpoints:
- POST `/order/create`
- POST `/order/remove/{order}`
- POST `/order/recreate/{order}`

Where `{order}` is order id from database. Actually it is autoincrement number.

To build application please use:
```shell
composer install
composer build
```

To test application run:
```shell
composer test-build
composer test
```
