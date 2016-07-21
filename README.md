# FawnoEmail

Extend Network/Email/CakeEmail allowing image inline in templates.


Install
-------

Copy content of Lib folder into your app/Lib.

Usage
-----
Whenever you need to send email, ensure this class is loaded:
```php
App::uses('FawnoEmail', 'Lib/Network/Email');
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
