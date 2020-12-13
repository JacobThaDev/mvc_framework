# mvc_framework
A small PHP MVC Framework using composer for dependency management

Requirements
- PHP 7.2+
- MySQL 5.6+
- Composer
- Apache web server

```I do not using Nginx so if you need rules you'll have to write them yourself.```

Config file is located at `app/config.php`

After cloning, to get dependencies installed type `composer install` in the project directory. You should then see a `vendor` folder.This being a MVC framework, it's pretty much like you'd expect. All of your backend is done in `app/controllers`, the templates are in `app/views`, and database models are in `app/models`

I've build an Access Control List and included it as well, which gives certain permissions based on the visitors rank. It's used in `core/Controller.php` and access to specific controllers/actions can be seen in `core/Security.php`. I modeled it after Phalcon3.2's Access Control List and I'm sure it could use some improvements but it works pretty nicely.
