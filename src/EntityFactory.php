<?php

namespace Eventity;

interface EntityFactory
{
    /**
     * @return EventEntity
     */
    public function create();

    /**
     * @param Events[] $events
     *
     * @return EventEntity
     */
    public function replay(array $events);
}
