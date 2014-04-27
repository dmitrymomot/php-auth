php-auth
========

Simply authentication library


## Installation

This package is available via Composer:

```json
{
    "require": {
        "dmitrymomot/php-auth": "1.*"
    }
}
```

## Example of usage

### Usage adapter "file"
```php
$auth = new \Auth\Adapter\File;
$auth->setUsers(array('test_user' => array('password' => 'hashed_password', 'role' => 'user')));
```

Login
```php
$auth->login('test_user', 'password'); // returns boolean value
```

Get user
```php
$auth->getUser('guest') // returns current username or 'guest', if user isn't logged in
```

Get user role
```php
$auth->getRole() // returns string
```

Check login
```php
$auth->loggedIn() // returned true
```

Check login as
```php
$auth->loggedIn('admin') // returned false
$auth->loggedIn('user') // returned true
```

Logout
```php
$auth->logout(); // returns boolean value
```

### Usage adapter "database"

In composer.json add package php-activerecord/php-activerecord
```json
    "require": {
		"dmitrymomot/php-session": "1.*",
        "php-activerecord/php-activerecord":"dev-master"
    },
```
and update composer.

Set database config (read more in [php-activerecord docs](http://www.phpactiverecord.org/projects/main/wiki))
```php
$cfg = \ActiveRecord\Config::instance();
$cfg->set_connections(array(
	'development' => 'mysql://username_for_dev:password@localhost/username_for_dev',
	'production' => 'mysql://username:password@localhost/database_name'
));
```

Initialization
```php
$auth = new \Auth\Adapter\Database();
```

Initialization with custom model User
```php
class CustomUser implements \Auth\Model\UserInterface {
	//....realisation of interface
}

$model = '\Custom\Model\CustomUser'; // full path to class
$auth = new \Auth\Adapter\Database($model);
```

Login
```php
$auth->login('test_user', 'password'); // returns boolean value
```

Check login
```php
$auth->loggedIn() // returned true
```

Check login as
```php
$auth->loggedIn('admin') // returned false
$auth->loggedIn('user') // returned true
```

Get user
```php
$auth->getUser('guest') // returns instance of class \Auth\Model\User or 'guest', if user isn't logged in
```

Get user role
```php
$auth->getRole() // returns string
```

Logout
```php
$auth->logout(); // returns boolean value
```

Create new user
```php
$auth->createUser(array('username' => 'test_user', 'password' => 'some_password', 'email' => 'user@mail.com', ...)); // returns boolean value or array error messages
```

Update current user
```php
$auth->updateUser(array('username' => 'test_user', 'password' => 'some_password', ....)); // returns boolean value or array error messages
```

### Helpers

```php
echo \Auth\Auth::hash('admin'); // returns hashed string 'admin'
```
Also you can set hash key
```php
\Auth\Auth::hashKey = 'vv34r3v4c34r';
```


## License

The MIT License (MIT). Please see [License File](https://github.com/dmitrymomot/php-auth/blob/master/LICENSE) for more information.
