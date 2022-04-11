<?php

namespace App\Entity;

use App\Repository\CurrentUrlRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrentUrlRepository::class)]
class CurrentUrl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Url;

    #[ORM\Column(type: 'datetime_immutable')]
    private $TimeStamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->Url;
    }

    public function setUrl(string $Url): self
    {
        $this->Url = $Url;

        return $this;
    }

    public function getTimeStamp(): ?\DateTimeImmutable
    {
        return $this->TimeStamp;
    }

    public function setTimeStamp(\DateTimeImmutable $TimeStamp): self
    {
        $this->TimeStamp = $TimeStamp;

        return $this;
    }
}
