<?php


namespace App\Controllers;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Slim\Views\Twig as View;
use App\Entity\User as UserEntity;

class HomeController extends Controller
{

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function index($request, $response)
    {
        $em = $this->getEntityManager();

//        $user = $em->getRepository(UserEntity::class)->find($_SESSION['user']);
//
//       /* $this->setUserPassword($user, 'lol', $em);
//        var_dump($user);*/
        return $this->container->view->render($response, 'home.twig');
    }

    public function setUserPassword(UserEntity $user, $pass, EntityManager $em)
    {
        $user->setPassword($pass);

        $em->persist($user);
        $em->flush();
    }
}