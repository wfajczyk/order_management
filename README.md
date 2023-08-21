# Order Management
This is a simple order management application. It allows to create orders with api.

Endpoints:
- POST `/order/create`
- POST `/order/remove/{order}`
- POST `/order/recreate/{order}`

Where `{order}` is order id from database. Actually it is autoincrement number.

The entire development environment can be run using `docker`
```shell
docker-compose up
```
use php at `localhost:8080` and mysql at `localhost:3306`

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
