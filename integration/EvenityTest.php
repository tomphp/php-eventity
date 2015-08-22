<?php

namespace integration\Eventity;

use PHPUnit_Framework_TestCase;
use Eventity\Eventity;

final class EvenityTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_has_a_default_settings_factory_method()
    {
        $this->assertInstanceOf(Eventity::class, Eventity::getInstance());
    }

    /** @test */
    function it_returns_the_same_instance_from_the_factory_method()
    {
        $this->assertSame(Eventity::getInstance(), Eventity::getInstance());
    }
}
