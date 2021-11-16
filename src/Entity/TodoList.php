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
    private $List;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="todoList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserTodo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getList(): ?string
    {
        return $this->List;
    }

    public function setList(string $List): self
    {
        $this->List = $List;

        return $this;
    }

    public function getUserTodo(): ?string
    {
        return $this->UserTodo;
    }

    public function setUserTodo(string $UserTodo): self
    {
        $this->UserTodo = $UserTodo;

        return $this;
    }
}
