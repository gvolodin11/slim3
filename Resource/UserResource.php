<?php
//addfull
namespace App\Resource;

use App\AbstractResource;
use App\Entity\User;

/**
 * Class Resource
 * @package App
 */
class UserResource extends AbstractResource
{

    /**
     * @param $id
     *
     * @return string
     */
    public function get($id)
    {
        if ($id === null) {
            $users = $this->getEntityManager()->getRepository('App\Entity\User')->findAll();
            $users = array_map(function ($user) {
                return $this->convertToArray($user);
            },
                $users);
            $data = json_encode($users);
        } else {
            $data = $this->convertToArray($this->getEntityManager()->find('App\Entity\User', $id));
        }

        // @TODO handle correct status when no data is found...

        return json_encode($data);
    }

    // POST, PUT, DELETE methods...

    private function convertToArray(User $user)
    {
        return array(
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        );
    }
    public function put($id)
    {
        $app = \Slim\Slim::getInstance();

        $name = $app->request()->params('name');
        $email = $app->request()->params('email');

        // handle if $id is missing or $name or $email are valid etc.
        // return valid status code or throw an exception
        // depends on the concrete implementation

        /** @var User $user */
        $user = $this->getEntityManager()->find('App\Entity\User', $id);
        // also check if $user has been found else handle correctly

        $user->setEmail($email);
        $user->setName($name);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return json_encode($this->convertToArray($user));
    }
}