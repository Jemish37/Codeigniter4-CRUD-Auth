# Mini Project Codeigniter 4
 

## Steps for Run Project

- Enable ext-intl extension of PHP, or uncomment ext-intl line in php.ini
- Create Database for the Project named "demo"
- update configuration for database in .env file;
- run below commands in terminal
- composer install
- php spark migrate 
- php spark db:seed UserSeeder  
- php spark db:seed CitySeeder
- run project via command in terminal "php spark serve" and navigate to respective url of project (default http://localhost:8080)
- Give permission to public folder it cause permission issue while uploading files

