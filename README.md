# Eventity

[![Software License](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE)

An experimental library for event sourcing.

## Example of current functionality

```php
<?php

namespace Example;

class User
{
    private $emailAddress;

    public function changeEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }
}

$userFactory = \Eventity\Eventity::getInstance()->getFactoryFor(User::class);

$user = $userFactory->create();
$user->changeEmailAddress('test@example.com');

var_dump($user->getNewEvents());

/*
OUTPUT:

array(2) {
  [0]=>
  object(Eventity\Event)#23 (2) {
    ["name":"Eventity\Event":private]=>
    string(6) "Create"
    ["entity":"Eventity\Event":private]=>
    string(12) "Example\User"
  }
  [1]=>
  object(Eventity\Event)#24 (2) {
    ["name":"Eventity\Event":private]=>
    string(18) "changeEmailAddress"
    ["entity":"Eventity\Event":private]=>
    string(12) "Example\User"
  }
}
*/
```
