<?php

namespace App\Entity;

use App\Repository\TodoListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoListRepository::class)
 */
class TodoList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $list;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="todoList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userTodo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getList(): ?string
    {
        return $this->list;
    }

    public function setList(string $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getUserTodo(): ?User
    {
        return $this->userTodo;
    }

    public function setUserTodo(?User $userTodo): self
    {
        $this->userTodo = $userTodo;

        return $this;
    }
}
