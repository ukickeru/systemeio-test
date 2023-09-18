# Systeme IO test

## How to build & run project

This project contains basic [Docker setup](/docker) for dev purposes.

You can use the following [make](/makefile) commands to control the Docker build:

* **Init**: ```make init``` - initializes the project

* **Build**: ```make build``` - rebuilds php image

* **Up**: ```make up```

* **Stop**: ```make stop```

* **Down**: ```make down```

* **Remove**: ```make remove``` - completely removes project

* **Grum**: ```make grum``` - runs code-quality tools, configured in [grumphp.yml](/grumphp.yml)

* **E2E tests**: ```make e2e``` - runs e2e tests, located in [tests/E2E](/tests/E2E)

You may configure build by some variables in [.env](/dev/.env) file.

## How to use API

By default, API available at [http://app.localhost:8085/](http://app.localhost:8085/).

### Query examples

_All query examples below written in cURL format._<br>
_You also may use prepared [postman.collection](./postman_collection.json)._

#### Calculate price

Query:
```shell
curl --location 'http://app.localhost:8085/payments/calculate-price' \
    --header 'Content-Type: application/json' \
    --data '{
     "productId": 1,
     "taxNumber": "DE123456789",
     "couponCode": "PERCENT_1"
    }'
```

Response example - status 200, json format body:
```json
{
    "price": 111.86
}
```

#### Pay

Query:
```shell
curl --location 'http://app.localhost:8085/payments/pay' \
    --header 'Content-Type: application/json' \
    --data '{
        "productId": 1,
        "taxNumber": "DE123456789",
        "couponCode": "ABSOLUTE_1",
        "paymentProcessor": "PAYPAL"
    }'
```

Response - status 200, empty json body

### Error response format

Response example - status 400, json format body:
```json
{
    "errors": [
        {
            "message": "This value should be greater than or equal to 1.",
            "code": "ea4e51d1-3342-48bd-87f1-9e672cd90cad",
            "path": "productId"
        },
        {
            "message": "Invalid tax number \"DE12345678\".",
            "code": null,
            "path": "taxNumber"
        },
        {
            "message": "The value you selected is not a valid choice.",
            "code": "8e179f1b-97aa-4560-a02f-2a8b42e49df7",
            "path": "paymentProcessor"
        },
        {
            "message": "Entity \"App\\Payments\\Entity\\Coupon\" with code \"ABS12\" doesn't exist.",
            "code": null,
            "path": "couponCode"
        }
    ]
}
```

## About architecture

Folders destination:
* ```src/```
  * ```Payments/``` - payments domain
  * ```Shared/``` - common code
* ```tests/```
  * ```E2E/``` - end-to-end tests (very simple)
  * ```Payments/``` - payments domain tests (unit & integration)
  * ```Shared/``` - tests for common mechanisms

_Please pay attention to the comments in the code - they contain good implementation tips and vision for further development_
