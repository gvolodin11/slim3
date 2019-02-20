<?php

/*
namespace App\Controllers;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Slim\Views\Twig as View;
use App\Entity\User as UserEntity;

class InfoController extends Controller
{

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
  /* public function showMainInfo($request, $response)
    {
        $em = $this->getEntityManager();

        $user = $em->getRepository(UserEntity::class)->find(1);

        return $this->container->view->render($response, 'info.html.twig', ['user' => $user]);
    }
}