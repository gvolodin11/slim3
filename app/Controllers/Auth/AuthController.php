<?php


namespace App\Controllers\Auth;

use App\Entity\User;
use App\Controllers\Controller;
use App\Validation\Validator;
use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;


class AuthController extends Controller
{
    public function getSignOut($request, $response)
    {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function postSignIn($request, $response)
    {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth) {
            $this->flash->addMessage('error','Could not sign you in with those details.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }

    /**
     * @param $request
     * @param $response
     * @return mixed
     */
    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postSignUp(Request $request, Response $response)
    {
        /** array $params */
        $params = $request->getParams();
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

//        if (empty($params['email']))
//        {
//            return false;
//        }

        $validation = new Validator();
        $validation->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable($em),
            'name' => v::notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));

        }

        $user = new User();

        $user->setEmail($params['email']);
        $user->setName($params['name']);
        $user->setPassword(password_hash($params['password'], PASSWORD_DEFAULT));

        $em->persist($user);
        //$em->remove();//delete user
        $em->flush();

        $this->flash->addMessage('info', 'You have been signed up!');


        return $response->withRedirect($this->router->pathFor('home'));
    }
}