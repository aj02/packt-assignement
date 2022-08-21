# Packt Assignment
## _Laravel 9 and Vue 3_

Pre-requisite to run this project

```sh
Install docker and docker-compose
```

## Installation
1. Clone the repo
```sh
git clone https://github.com/aj02/packt-assignement.git packt-app
```
2. Free all the required ports 
```sh
sudo service mysql stop
sudo service nginx stop
sudo service apache2 stop
```
3. Build the app
```sh
sudo chown $USER:$USER packt-app
git checkout packt-assignment
```
(use Terminal 1)
```sh
vendor/bin/sail up -d
#vendor/bin/sail composer require laravel/jetstream
#vendor/bin/sail artisan jetstream:install inertia
#vendor/bin/sail artisan migrate
```
(use Terminal 2)
```sh
vendor/bin/sail npm install
vendor/bin/sail npm run dev 
```
(use Terminal 3)
```sh
vendor/bin/sail npm run watch
```

4. Visit http://0.0.0.0/

5. Kill docker containers and start afresh
```sh
docker kill $(docker ps -q)
docker system prune -a
```
