<?php

namespace App\Auth;


use App\Entity\User;
use Doctrine\ORM\EntityManager;

class Auth
{


    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function user()
    {
        // var_dump($_SESSION['user']); die();
        $container = $this->getContainer();  // получил массив с данными(контейнер)
        /** @var EntityManager $em */
        $em = $container['em']; //достал элемент из массива(энтити)

        if (empty($_SESSION['user'])) {
            return false;
        }

        $user = $em->getRepository(User::class)->find($_SESSION['user']);

        return $user;
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password)
    {
        $em = $this->getContainer()['em'];

        /** @var User $user */
        $user = $em->getRepository(User::class)->findBy(['email' => $email])[0];

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
            return true;
        }

        return false;
        // if !user return false
        // verify password for that user
        // set info session
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }


}