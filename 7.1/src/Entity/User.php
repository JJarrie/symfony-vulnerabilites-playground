<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity('username')]
#[ORM\Table(name: 'app_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private(set) int $id {
        get => $this->id;
    }

    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Length(min: 3, max: 50)]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    public string $username {
        get => $this->username;
        set => $this->username = $value;
    }

    /** @var array<int, string> */
    #[ORM\Column(type: 'json')]
    public array $roles = [] {
        get => $this->roles;
        set => $this->roles = $value;
    }

    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Length(min: 6, max: 100)]
    #[ORM\Column(type: 'string', length: 100)]
    public string $password {
        get  => $this->password;
        set => $this->password = $value;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /*
     * These 2 methods are needed by symfony/security who don't use hook actually
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array {
        return $this->roles;
    }
}