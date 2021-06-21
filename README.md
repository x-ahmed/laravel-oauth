## Laravel Passport OAuth2

Laravel Passport provides a complete OAuth2 implementation out of the box.That
comes with database migrations, routes, and middleware to create an OAuth 2.0
server that will return access tokens to allow access to server resources by
other third-party apps. It uses the League OAuth2 Server package as a dependency.

### OAuth Server Setup

First, you need to clone this repository, and navigate to `server` and install
composer dependencies.

```
cd server
composer install
```

After installing composer dependencies, add your database credentials in `.env` file and then run migrations.

```
php artisan migrate
```

Make sure to create virtual host for `server` app for example `server.test` that
you will need to use in `client` app.

### Client App Setup

Now, navigate to `client` app and install
composer dependencies.

```
cd server
composer install
```

Next, add your database credentials in `.env` file and then run migrations.

```
php artisan migrate
```

Next, create virtual host for `client` app for example `client.test` that
you will need to define redirect uri for OAuth2 authorization callback.

### Usage

In the server app, navigate to `http://server.test/oauth` route. And here
create new OAuth client. It will generate client ID and secret.

Next, in the client app, rename `.env.example` to `.env` then add following configuration values.

```
OAUTH_SERVER_URI=http://server.test
OAUTH_SERVER_ID=<client-id>
OAUTH_SERVER_SECRET=<client-secret>
```
