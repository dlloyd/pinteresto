<?php

namespace App;


final class Events
{
    /**
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const IMAGE_REGISTERED = 'image.registered';
}
