<?php

namespace Eventity;

final class Event
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $entity;

    public function __construct($name, $entity)
    {
        $this->name = $name;
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
