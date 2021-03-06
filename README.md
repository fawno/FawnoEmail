[![Package at Packagist](https://img.shields.io/packagist/v/fawno/FawnoEmail.svg?style=plastic)](https://packagist.org/packages/fawno/fawnoemail)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=plastic)](https://raw.githubusercontent.com/fawno/FawnoEmail/CakePHP-3/LICENSE)

# FawnoEmail

Extend Cake\Mailer\Email allowing image inline in templates.


Install via composer
--------------------

Since v3, CakePHP uses [composer](http://getcomposer.org), the easiest way to set up is by either running
```
  composer require fawno/fawnoemail
```

Usage
-----
Whenever you need to send email, ensure this class is loaded:
```php
  use Fawno\Mailer\FawnoEmail;
```
Use the class as normal CakeEmail:
```php
  $email = new FawnoEmail();
  $email->to(array('example@example.com' => 'Example'));
  $email->subject('Example Email');
  $email->template('default');
  $email->send();
```

In template:
```php
  <img src="cid:/full/path/image">
  <img src="cid:///full/path/image">
  <img src="file:/full/path/image">
  <img src="file:///full/path/image">
  echo $this->Html->image('cid:///full/path/image');
  echo $this->Html->image('file:///full/path/image');
```
