# FawnoEmail

Extend Network/Email/CakeEmail allowing image inline in templates.


Install via composer
--------------------

Since v3, CakePHP uses [composer](http://getcomposer.org), the easiest way to set up the Bootstrap helpers is by either running
```
  composer require fawno/fawnoemail
```
or adding the following to your composer.json and run composer update:
```composer.json
  "require": {
    "fawno/fawnoemail": "~1.0"
  }
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
