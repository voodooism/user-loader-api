# User loader API
API that imports data from a 3rd party API, saves it in DB and can display it.
The project is designed using the Symfony framework.

## Installation

```bash
cd /path/to/project/directory
git clone git@github.com:voodooism/user-loader-api
composer install

docker-compose up -d

//It's necessary to migrate migrations inside php container
./bin/console do:mi:mi
```

## Usage

Command to upload users from 3rd party API:

```bash
./bin/console app:load-customers <int>count
```

Endpoint:
- http://localhost:8788/api/v1/customers