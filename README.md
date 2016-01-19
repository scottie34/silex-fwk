## Main components
[Silex](http://silex.sensiolabs.org/) as main framework
* why ? lightweight, curiosity

[A (quick win) Fork of Microrest](https://github.com/scottie34/microrest.php)
* why ? raml definition, routes management,
* Decorate the RestController to manage request parameter and response format

Curl + phpQuery

## Install
Firstly
```
git clone + composer install
```

### change database connection properties
* currently a basic mySql database called *silexfwk* 
* database connection properties can be changed in config/prod.php
* one can find mySql scripts available in db folder

### change vhost configuration
* the *baseUri* in silex-fwk/app/api.raml must match your *vhost* configuration (currently *silex-fwk.dev* is used)

## How-To
### collect posts
```
php web/console.php GET /api/collect
```
### rest API available at baseUri/api 
    

## Known Issue (not matching requirements)
* encoding in JSonResponse
