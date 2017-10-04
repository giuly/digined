# Digined

## Model 

Use	Facebook’s API	to query	data for the CocaColaNetherlands FB page. Build	3	REST endpoints that return JSON for following:
- Latest	20	posts	by	the	CocaColaNetherlands
- Top	5	users	who	have	liked	most	of	these	20	posts
- Structure of latest 20 posts, ordered based on the number of likes they receive, along with the number of likes each post has received.	

## Install (e.g. Ubuntu 16.04 )

1. System configuration apart from php-fpm, nginx/apache etc.
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

Initialization (Add config details to a MongoDB collection): 
1. Uncomment line 20 (application/controller/Api.php)
2. \*Acces application via browser or Postman (You should see a message like this: Inserted with Object ID '59d4dadc4fdf0304116b6bd3')
3. Commnent it back (line 20).

\* You can access any of the 3 available endpoints: 
  - http://yourlocal/api/get_latest_posts 
  - http://yourlocal/api/get_users_posts_likes
  - http://yourlocal/api/get_posts_ordered

## Request & Response

API Requests: 
  - GET api/get_latest_posts
  - GET api/get_users_posts_likes
  - GET api/get_posts_ordered
  
API Response(JSON): 
```shell
{
    "status": 200,
    "data": {
        "0": {
            "id": "901287109904861_1693243807375850",
            "message": "Recunoaste! De cate ori nu ai facut si tu la fel? <3 #TasteTheFeeling",
            "created_time": "2017-09-06T08:47:37+0000",
            "number_of_likes": 18561
        },
        ...
```

## Recommendation
Postman as tool to access API endpoints




