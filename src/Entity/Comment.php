<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
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

    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timedate;

    /**
     * @ORM\ManyToOne(targetEntity=Videogame::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idvideogame;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTimedate(): ?string
    {
        return $this->timedate;
    }

    public function setTimedate(string $timedate): self
    {
        $this->timedate = $timedate;

        return $this;
    }

    public function getIdvideogame(): ?Videogame
    {
        return $this->idvideogame;
    }

    public function setIdvideogame(?Videogame $idvideogame): self
    {
        $this->idvideogame = $idvideogame;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
