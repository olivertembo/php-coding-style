## PHP Test

## About the Project

This projects highlights the use of php with a modern mvc framework(Laravel). It utilize the following.

1. PHP 8
2. Laravel 8
3. MySQL
4. Tailwind css framework
5. docker

## Prerequisites

This project will work on any machine that has Docker installed.

1. MacOs/Windows/Linux
2. Docker

## STARTING LOCAL Environment

### On MacOs

1. Clone repo
2. Run the following commands in the terminal in root directory of cloned repo
   Run `docker-compose up` This will create new container in docker then start it  
   Run `composer update` This should update all dependancies of composer  
   Run `npm install` npm dependancy packages  

3. Run the following commands to create initial users for the application
   `php artisan migrate` :to migrate databases  
   `php artisan db:seed` : to seed initial data  
   `php artisan storage` : to make a symlink for image storage  

4. View project at: localhost:80

5. Use the following credentials to log in
   user_1@example.com, password_1
   user_2@example.com, password_2

### On Windows | using command prompt

1. Clone repo
2. Run the following commands in the terminal in root directory of cloned repo
   Run `docker-compose up` This will create new container in docker then start it
   Run `composer update` This should update all dependancies of composer
   Run `npm install` npm dependancy packages

3. Run the following commands to create initial users for the application
   `php artisan migrate` :to migrate databases
   `php artisan db:seed` : to seed initial data
   `php artisan storage` : to make a symlink for image storage

4. View project at: localhost:80

5. Use the following credentials to log in
   user_1@example.com password_1
   user_2@example.com password_2

## Project Structure

## Use the following .env file

APP_NAME="PHP TEST"  
APP_ENV=local  
APP_KEY=base64:s9xEkDOHUb4lJBrJxM7riqJVR8RoYUfPDjI/hZPv2B0=  
APP_DEBUG=true  
APP_URL=http://example-app.test  

LOG_CHANNEL=stack  
LOG_LEVEL=debug  

DB_CONNECTION=mysql  
DB_HOST=mysql  
DB_PORT=3306  
DB_DATABASE=example_app  
DB_USERNAME=sail  
DB_PASSWORD=password  

BROADCAST_DRIVER=log  
CACHE_DRIVER=file  
FILESYSTEM_DRIVER=local  
QUEUE_CONNECTION=sync  
SESSION_DRIVER=file  
SESSION_LIFETIME=120  

MEMCACHED_HOST=memcached  

REDIS_HOST=redis  
REDIS_PASSWORD=null  
REDIS_PORT=6379  

MAIL_MAILER=smtp  
MAIL_HOST=mailhog  
MAIL_PORT=1025  
MAIL_USERNAME=null  
MAIL_PASSWORD=null  
MAIL_ENCRYPTION=null  
MAIL_FROM_ADDRESS=null  
MAIL_FROM_NAME="${APP_NAME}"  

AWS_ACCESS_KEY_ID=  
AWS_SECRET_ACCESS_KEY=  
AWS_DEFAULT_REGION=us-east-1  
AWS_BUCKET=  
AWS_USE_PATH_STYLE_ENDPOINT=false  
  
PUSHER_APP_ID=  
PUSHER_APP_KEY=  
PUSHER_APP_SECRET=  
PUSHER_APP_CLUSTER=mt1  
  
MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"  
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"  
  
SCOUT_DRIVER=meilisearch  
MEILISEARCH_HOST=http://meilisearch:7700  
   