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


#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/', name: 'reg', methods: ['POST'])]
    public function registration(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHash): Response
    {
        $login = $request->get('login');
        $pasword = $request->get('passwor');
        $content = $request->getContentType();

        if(!isset($login)) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't login in json file"
            ]);
        }

        if(!isset($password)) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't password in json file"
            ]);
        }

        if($content != 'json') {
            return $this->json ([
                'status'=>400,
                'message'=>"We accept only json file"
            ]);
        }

        if(0 !== count($userRepository->findBy(['login'=>$login]))) {
            return $this->json ([
                'status'=>400,
                'message'=>"Such a user is already registered"
            ]);
        }

        $user = new User();
        $user->setLogin($login);
        $user->setPassword($userPasswordHash->hashPassword($user, $request->get('password')));

        $ent = $this->getDoctrine()->getManager();
        $ent->persist($user);
        $ent->flush();

        return $this->json([
            'status'=>200,
            'message'=>"Congratulations, everything is fine"
        ]);
    }

}
