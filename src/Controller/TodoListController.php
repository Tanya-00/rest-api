<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoList1Type;
use App\Repository\TodoListRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo/list')]
class TodoListController extends AbstractController
{
    #[Route('/', name: 'todos', methods: ['GET'])]
    public function todos(Request $request, UserRepository $userRepository, TodoListRepository $todoRepository) : Response {

        $content = $request->getContentType();
        $data = json_decode($request->getContent(), true);

        if($content != 'json') {
            return $this->json ([
                'status'=>400,
                'message'=>"We accept only json file"
            ]);
        }

        if(!isset($data['login'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't login in json file"
            ]);
        }

        if($data['login'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Login wasn't specified"
            ]);
        }

        if(!isset($data['password'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't password in json file"
            ]);
        }

        if($data['password'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Password wasn't specified"
            ]);
        }

        $user = $userRepository->findOneByLogin($data["login"]);

        if($user == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Invalid login"
            ]);
        }

        if($user->getPassword() !== $data['password']) {
            if($user == null) {
                return $this->json ([
                    'sratus'=>400,
                    'message'=>"Invalid password"
                ]);
            }
        }

        if($user !== null && $user->getPassword() == $data['password']) {
            $todoList = $todoRepository->findBy(["user"=>$user]);

            foreach($todoList as $todo) {
                $array = [
                    "id"=>$todo->getId(),
                    "list"=>$todo->getList()
                ];
                $result[] = $array;
            }

            return $this->json ([
                'sratus'=>200,
                'todoList'=>$result
            ]);
        }
        else {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There is no such user"
            ]);
        }

    }

    #[Route('/', name: 'createTodo', methods: ['POST'])]
    public function createTodo(Request $request, UserRepository $userRepository) : Response {
        $content = $request->getContentType();
        $data = json_decode($request->getContent(), true);

        if($content != 'json') {
            return $this->json ([
                'status'=>400,
                'message'=>"We accept only json file"
            ]);
        }

        if($data == null) {
            return $this->json ([
                'status'=>400,
                'message'=>"The file cannot be empty"
            ]);
        }

        if(!isset($data['login'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't login in json file"
            ]);
        }

        if($data['login'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Login wasn't specified"
            ]);
        }

        if(!isset($data['password'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't password in json file"
            ]);
        }

        if($data['password'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Password wasn't specified"
            ]);
        }

        if(!isset($data['list'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't to do list in json file"
            ]);
        }

        if($data['list'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"List wasn't specified"
            ]);
        }

        $user = $userRepository->findOneByLogin($data["login"]);

        if($user == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Invalid login"
            ]);
        }

        if($user->getPassword() !== $data['password']) {
            if($user == null) {
                return $this->json ([
                    'sratus'=>400,
                    'message'=>"Invalid password"
                ]);
            }
        }

        if($user !== null && $user->getPassword() == $data['password']) {
            $todoL = new TodoList();
            $todoL->setList($data['list']);
            $todoL->setUser($user);

            $user->addTodo($todoL);

            $ent = $this->getDoctrine()->getManager();
            $ent->persist($user);
            $ent->persist($todoL);
            $ent->flush();

            return $this->json ([
                'sratus'=>200,
                'message'=>"Juhu, you have created todo list"
            ]);
        }
        else {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Something went wrong and todo list didn't appear:("
            ]);
        }
    }

    #[Route('/ {id}', name: 'deleteTodo', methods: ['DELETE'])]
    public function deleteTodo(Request $request, UserRepository $userRepository, TodoListRepository $todoRepository) : Response {
        $content = $request->getContentType();
        $data = json_decode($request->getContent(), true);

        if($content != 'json') {
            return $this->json ([
                'status'=>400,
                'message'=>"We accept only json file"
            ]);
        }

        if(!isset($data['login'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't login in json file"
            ]);
        }

        if($data['login'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Login wasn't specified"
            ]);
        }

        if(!isset($data['password'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't password in json file"
            ]);
        }

        if($data['password'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Password wasn't specified"
            ]);
        }

        $user = $userRepository->findOneByLogin($data["login"]);

        if($user == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Invalid login"
            ]);
        }

        if($user->getPassword() !== $data['password']) {
            if($user == null) {
                return $this->json ([
                    'sratus'=>400,
                    'message'=>"Invalid password"
                ]);
            }
        }

        if($user !== null && $user->getPassword() == $data['password']) {
            $todoL = $todoRepository->findBy($id);

            if($todoL == null) {
                return $this->json ([
                    'sratus'=>400,
                    'message'=>"Todo list wasn't found"
                ]);
            }
            else {
                $user->removeTodo($todoL);

                $ent = $this->getDoctrine()->getManager();
                $ent->remove($todoL);
                $ent->flush();

                if($user == null) {
                    return $this->json ([
                        'sratus'=>200,
                        'message'=>"Todo list successfully deleted"
                    ]);
                }
            }
        }
        else {
            return $this->json ([
                'sratus'=>400,
                'message'=>"You can't delete this todo list"
            ]);
        }
    }

    #[Route('/{id}', name: 'editTodo', methods: ['PUT'])]
    public function editTodo(Request $request, UserRepository $userRepository, TodoListRepository $todoRepository) : Response {
        $content = $request->getContentType();
        $data = json_decode($request->getContent(), true);

        if($content != 'json') {
            return $this->json ([
                'status'=>400,
                'message'=>"We accept only json file"
            ]);
        }

        if(!isset($data['login'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't login in json file"
            ]);
        }

        if($data['login'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Login wasn't specified"
            ]);
        }

        if(!isset($data['password'])) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"There isn't password in json file"
            ]);
        }

        if($data['password'] == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Password wasn't specified"
            ]);
        }

        $user = $userRepository->findOneByLogin($data["login"]);

        if($user == null) {
            return $this->json ([
                'sratus'=>400,
                'message'=>"Invalid login"
            ]);
        }

        if($user->getPassword() !== $data['password']) {
            if($user == null) {
                return $this->json ([
                    'sratus'=>400,
                    'message'=>"Invalid password"
                ]);
            }
        }

        if($user !== null && $user->getPassword() == $data['password']) {
            $todoL = $todoRepository->findBy($id);

            if($todoL == null) {
                return $this->json ([
                    'sratus'=>400,
                    'message'=>"Todo list wasn't found"
                ]);
            }
            else {
                $todoL->setList($data['list']);

                $ent = $this->getDoctrine()->getManager();
                $ent->persist($todoL);
                $ent->flush();

                return $this->json ([
                    'sratus'=>200,
                    'message'=>"The changes were made successfully"
                ]);
            }
        }
        else {
            return $this->json ([
                'sratus'=>400,
                'message'=>"You can't changes this todo list"
            ]);
        }

    }

}