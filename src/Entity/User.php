<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FIO;

    /**
     * @ORM\OneToMany(targetEntity=TodoList::class, mappedBy="user", orphanRemoval=true)
     */
    private $TodoList;

    public function __construct()
    {
        $this->todoList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFIO(): ?string
    {
        return $this->FIO;
    }

    public function setFIO(string $FIO): self
    {
        $this->FIO = $FIO;

        return $this;
    }

    /**
     * @return Collection|TodoList[]
     */
    public function getTodoList(): Collection
    {
        return $this->TodoList;
    }

    public function addTodoList(TodoList $todoList): self
    {
        if (!$this->todoList->contains(todoList)) {
            $this->todoList[] = todoList;
            todoList->setUser($this);
        }

        return $this;
    }

    public function removeTodoList(TodoThingy todoList): self
    {
        if ($this->todoList->removeElement(todoList)) {
            // set the owning side to null (unless already changed)
            if (todoList->getUser() === $this) {
                todoList->setUser(null);
            }
        }

        return $this;
    }
}
