<?php

namespace Eventity;

interface EventEntity
{
    /**
     * @return Event[]
     */
    public function getNewEvents();
}
