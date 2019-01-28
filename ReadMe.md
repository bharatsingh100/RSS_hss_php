This is about SamparkSystem.

## Local Development Environment Setup
The local environment is built using [Docker4PHP Container](https://github.com/wodby/docker4php) setup.

1. Make sure Docker is installed and running
1. Checkout Code
1. Change Directory to the repo root folder `cd sampark`
1. Create local config settings file 
    1. `cp system/application/config/config.php.docker system/application/config/config.php` 
    1. `cp system/application/config/database.php.docker system/application/config/database.php`
    1. `cp .htaccess-default .htaccess`
1. Run `make -f docker.mk up`
   - This will download Docker images
   - and Load the [initial DB Dump](mariadb-init/db-dump-sanitized-jan25.sql) into MySQL
1. Run the `make -f docker.mk logs` to make sure initial database has been loaded.
1. Access the site using http://sampark.lvh.me:8000
   - The DNS entry points to 127.0.0.1 so that you don't have to modify hosts file
   - You can manually connect to the DB server on localhost port 3307 using following credentials 
   ```
   Host: localhost (mariadb insdie VM)
   DB Name: php
   User: php
   Password: php
   ```
1. Login using username: `uid1@mailinator.com` , password: `password`. All of the accounts have had their password set to `password`.
1. Any email sent from this system will be captured internally by Mailhog and accessible from http://mailhog-sampark.lvh.me:8000/

### Using Docker Wodby Environment
- Access Shell for Web Docker Image `make -f docker.mk shell`
- Stop Containers `make -f docker.mk down`
- Start Containers `make -f docker.mk up`
- Destroy Containers, including existing Database content `docker-compose down`
- See Logs `make -f docker.mk logs`
