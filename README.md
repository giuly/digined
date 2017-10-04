# Digined

## Model 

Use	Facebook’s API	to query	data for the CocaColaNetherlands FB page. Build	3	REST endpoints that return JSON for following:
- Latest	20	posts	by	the	CocaColaNetherlands
- Top	5	users	who	have	liked	most	of	these	20	posts
- Prepare	a	data	structure	of	latest	20	posts,	ordered	based	on	the	number	of	likes	they	

## Install (e.g. Ubuntu 16.04 )

1. System configuration apart from php-fpm, nginx/apach etc.
```shell
pecl install mongodb
apt-get install php7.0-dev
```
2. Run composer:
```shell
composer require jenssegers/mongodb --ignore-platform-reqs
```
3. Move *vendor* folder under *application* folder

## Application Config

Initialization: 
1. Uncomment line 20 (application/controller/Api.php)
2. \*Acces application via browser or Postman (You should see a message like this: Inserted with Object ID '59d4dadc4fdf0304116b6bd3')
3. Commnent back line 20.
\* You can access any of the 3 available endpoints: 
  http://yourlocal/api/**get_latest_posts** 
  http://yourlocal/api/**get_users_posts_likes**
  http://yourlocal/api/**get_posts_ordered**




