
# ML Test  
  
## Usage  
  
Start by copying `.env.example` to `.env`, if running this via docker you   
shouldn't need make any changes.  
  
Run `composer install`.  
  
To bring up the docker containers run:  
  
```  
docker-compose up -d --build  
```  
  
Once the containers are up and running you can import the SQL into MySQL by running:  
  
```  
docker exec -i api-mysql sh -c 'exec mysql -uroot -p"password"' < data.sql  
```  
  
Finally run the following command to create the Doctrine ORM proxy classes:  
  
```  
docker exec -it -w /app api-ml ./vendor/bin/doctrine orm:generate-proxies  
```   
Next checkout the https://github.com/pmill/ml-ui project to experience the UI.  
  
## Tests  
  
To run unit tests execute the following command:  
  
```  
./vendor/bin/phpunit  
```  
  
## General Requirements  
  
> When creating a subscriber email must be in valid format and host domain must be active 

The validation rules for the create subscriber request can be found in the `CreateSubscriberValidator` validator class. The code to validate the host domain can be found in the `EmailHostValidationRule` validation rule class

> No framework but you can use packages  

This project does not use a framework but pulls in some commonly used packages for routing, dependency injection, ORM, validation, and caching.

> Use of relationships  

The database tables for this project make use of relationships, these are modelled in the code.

> Validate request before calling the controller 

The request validators are defined on the routes (in `routes/api.php`), the custom router takes care of validating the requests before instantiating and calling the controller. Another way to do this would've been to use implement a middleware system like Laravel and other frameworks use.

> Optional: Redis for caching 

Redis is used to cache the DNS results in the `EmailHostValidationRule` validator rule class.

> Optional: Write some tests

I've added a sample of unit tests in the `tests/` folder, please do let me know if you'd like me to expand/add to these.
 