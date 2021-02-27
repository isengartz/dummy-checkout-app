## About 
Super dummy implementation of product buy functionality. It supports multiple products but the UI sucks (I should add ajax cart functionality) !
## Deploy Methods

### 1. Via Docker / docker-compose
- cd into the project root
- run `mv .env.example .env`
- run `composer install`
- run `npm install`
- run `docker-composer up`

The project should be deployed at `http://127.0.0.1:5050`

**:sos: IMPORTANT: :sos:**
the only env variables that you need to setup while deploying via docker is `MAIL_USERNAME` , `MAIL_PASSWORD` and
`MAIL_FROM_ADDRESS`. The default MAIL driver is `mailtrap`.

**The database migration  / seeding will be automatically handled from docker.**
### 2. Manually Deploy

| :collision: Requirements :collision: |
|--------------------------------------|
| `php7.4+`                            |
| `php-redis` extension installed      |
| `php-intl`  extension installed      |
| `redis` running locally              |



- cd into the project root
- run `mv .env.example .env` And setup the env variables
- run `composer install`
- run `npm install`
- run `php artisan migrate && php arisan db:seed`
- run `npm run watch`
- run `php artisan serve`


## Testing
Wrote some  very basic tests !  :wheelchair: :wheelchair: :wheelchair:

You can run `php artisan test` to run all the tests

If you deployed via `docker` you need to run the tests from the inside of `app` Service (`eshop-app` container name )
