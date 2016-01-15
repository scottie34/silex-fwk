## Main components
[silex](http://silex.sensiolabs.org/) as main framework
* why ? lightweight, curiosity

[a (quick win) fork of microrest](https://github.com/scottie34/microrest.php)
* why ? raml definition, routes management,
* allow to Decorate the RestController to manage request parameter and response format

Curl + phpQuery

## How to
collect posts : php web/console.php GET /api/collect

## Known Issue (not matching requirements)
* no unit-test: focus on delivering the main source files firstly (short deadline)
* 'created' attribute instead of 'date' in JSON Response
* 'date' don't take time into account
