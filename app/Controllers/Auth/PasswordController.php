<?php


namespace App\Controllers\Auth;

use App\Entity\User;
use App\Controllers\Controller;
use App\Validation\Validator;
use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;


/**
 * Class PasswordController
 * @package App\Controllers\Auth
 */
class PasswordController extends Controller
{
    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'auth/password/change.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postChangePassword($request, $response)
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        /** @var User $user */
        $user = $this->auth->user();

        if (empty($user)) {
            return false;
        }

        $validation = $this->validator->validate($request,[
            'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($user->getPassword()),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }

        $user->setPassword(password_hash($request->getParam('password'), PASSWORD_DEFAULT));

        //записать изменения ентити в бд
        $em->persist($user);
        $em->flush();

        return $response->withRedirect($this->router->pathFor('home'));
    }
}