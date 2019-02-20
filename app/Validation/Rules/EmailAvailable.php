<?php

namespace App\Validation\Rules;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{

    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param $input
     * @return bool
     */
    public function validate($input)
    {
        /** @var EntityManager $em */
        $em = $this->em;

        if ($email = $em->getRepository(User::class)->findBy(['email' => $input])) {
            return false;
        } else {
            return true;
        }
    }
}