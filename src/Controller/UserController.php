<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/reg", name="reg",methods={"POST"})
     */
    public function registration(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHash): Response
    {
        $login = $request->get('login');
        $password = $request->get('password');
        $content = $request->getContentType();

        $data = json_decode($request->getContent(), true);

        if(!isset($data['login'])) {
            return $this->json ([
                'status'=>400,
                'message'=>"There isn't login in json file"
            ]);
        }

        if(!isset($data['password'])) {
            return $this->json ([
                'status'=>400,
                'message'=>"There isn't password in json file"
            ]);
        }

        if($content != 'json') {
            return $this->json ([
                'status'=>400,
                'message'=>"We accept only json file"
            ]);
        }

        if($userRepository->findOneBy(["login"=>$data["login"]]) != null) {
            return $this->json ([
                'status'=>400,
                'message'=>"Such a user is already registered"
            ]);
        }

        $user = new User();
        $user->setLogin($data["login"]);
        $user->setPassword($data["password"]);

        $ent = $this->getDoctrine()->getManager();
        $ent->persist($user);
        $ent->flush();

        return $this->json([
            'status'=>200,
            'message'=>"Congratulations, everything is fine"
        ]);
    }

}
