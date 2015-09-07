# Eventity

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

## API

Some notes for developers:

### Eventity\Code

A short explanation of the `Eventity\Code` namespace:

* `Eventity\Code\Analyser` - takes a class name and produces an instance of
  `Eventity\Code\Definition\ClassDefinition` which describes the class.

* `Eventity\Code\Definition` - value objects which describe a class.

* `Eventity\Code\Renderer` - takes an `Eventity\Code\Definition\ClassDefinition`
  and creates the PHP code for the described class as a `string`.

* `Eventity\Code\Declarer` - takes an `Eventity\Code\Definition\ClassDefinition`
  and declares the class it describes (by using a renderer and eval).

* `Eventity\Code\Instantiater` - takes a class name and returns an instance of
  the class.
