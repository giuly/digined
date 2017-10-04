# Digined

## Model 

Use	Facebook’s API	to query	data for the CocaColaNetherlands FB page. Build	3	REST endpoints that return JSON for following:
- Latest	20	posts	by	the	CocaColaNetherlands
- Top	5	users	who	have	liked	most	of	these	20	posts
- Prepare	a	data	structure	of	latest	20	posts,	ordered	based	on	the	number	of	likes	they	

## Install

e.g. Ubuntu 16.04 

System configuration apart from php-fpm, nginx/apach etc.
```shell
pecl install mongodb
apt-get install php7.0-dev
```
Run composer:
```shell
composer require jenssegers/mongodb --ignore-platform-reqs
```
Move *vendor* folder under *application* folder

Application Configuarion
Update MongoDB
