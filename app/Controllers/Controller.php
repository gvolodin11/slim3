<?php

namespace App\Controllers;

use Doctrine\ORM\EntityManager;

class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($property)
    {
        if ($this->container->{$property}){
            return $this->container->{$property};
        }
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getEntityManager()
    {
        $container = $this->getContainer();

        /** @var EntityManager $em */
        $em = $container['em'];

        return $em;
    }

}