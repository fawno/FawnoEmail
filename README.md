# FawnoEmail

Extend Network/Email/CakeEmail allowing image inline in templates.


Install
-------

Copy content of Lib folder into your app/Lib.

Install via composer
--------------------

If uses [composer](http://getcomposer.org), running
```
  composer require fawno/fawnoemail dev-CakePHP-2
```
And add in app/Config/bootstrap.php
```php
  App::build(array('Lib', array(ROOT . '/vendors/fawno/fawnoemail/')));
```
Usage
-----
Whenever you need to send email, ensure this class is loaded:
```php
  App::uses('FawnoEmail', 'Lib/Network/Email');
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
