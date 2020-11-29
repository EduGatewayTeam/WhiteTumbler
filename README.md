
[![CodeFactor](https://www.codefactor.io/repository/github/edugatewayteam/whitetumbler/badge)](https://www.codefactor.io/repository/github/edugatewayteam/whitetumbler)

# WhiteTumbler: easy control of BigBlueButton

## Doctrine

### Migrations

Running migrations: 
```
php artisan doctrine:migrations:migrate
```

More documentation on [official site](http://www.laraveldoctrine.org/docs/1.4/migrations/introduction)

## Development with XAMPP

1. Download xampp [installer](https://downloadsapachefriends.global.ssl.fastly.net/7.4.10/xampp-windows-x64-7.4.10-0-VC15-installer.exe?from_af=true) and install in path `C:\xampp`
1. Download custom configuration with PostgreSQL and phpPgAdmin [configuration](https://cloud.mail.ru/public/5bfR%2F5s234kK49)
1. Extract archive to root of disk `С:\` with file replacement
1. Run xampp, then you will be able to use phpPgAdmin on http://127.0.0.1/phpPgAdmin/
1. Default user and password is root:root

## Development with Docker

1. Copy and rename docker-compose.yml.example to docker-compose.yml
1. Run docker-compose build && docker-compose up -d
