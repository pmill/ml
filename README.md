# ML Test

## Usage

Start by copying `.env.example` to `.env`, if running this via docker you 
shouldn't need make any changes.

Run `composer install`.

To bring up the docker containers run:

```
docker-compose up -d --build
```

Once the containers are up and running you can import the SQL into MySQL by running:

```
docker exec -i api-mysql sh -c 'exec mysql -uroot -p"password"' < data.sql
```

Finally run the following command to create the Doctrine ORM proxy classes:

```
docker exec -it -w /app api-ml ./vendor/bin/doctrine orm:generate-proxies
``` 

Next checkout the https://github.com/pmill/ml-ui project to experience the UI.

## Tests

To run unit tests execute the following command:

```
./vendor/bin/phpunit
```
