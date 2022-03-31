<?php

namespace App\Entity;

use App\Entity\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{
    use EntityTrait;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\ManyToOne(targetEntity: TaskList::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $list;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getList(): ?TaskList
    {
        return $this->list;
    }

    public function setList(?TaskList $list): self
    {
        $this->list = $list;

        return $this;
    }
}
