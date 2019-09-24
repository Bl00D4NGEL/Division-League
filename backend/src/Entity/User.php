<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    const ROLES = [self::ROLE_NORMAL, self::ROLE_MODERATOR, self::ROLE_ADMIN];
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_MODERATOR = 'MODERATOR';
    const ROLE_NORMAL = 'NORMAL';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $loginName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="date")
     */
    private $lastLoggedIn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoginName(): ?string
    {
        return $this->loginName;
    }

    public function setLoginName(string $loginName): self
    {
        $this->loginName = $loginName;

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

    public function getLastLoggedIn(): ?\DateTimeInterface
    {
        return $this->lastLoggedIn;
    }

    public function setLastLoggedIn(\DateTimeInterface $lastLoggedIn): self
    {
        $this->lastLoggedIn = $lastLoggedIn;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        if (!in_array($role, self::ROLES)) {
            throw new \InvalidArgumentException("Invalid role");
        }
        $this->role = $role;

        return $this;
    }

    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->getPassword());
    }

    public function asArray()
    {
        return [
            'id' => $this->getId(),
            'role' => $this->getRole(),
            'lastLoggedIn' => $this->getLastLoggedIn(),
        ];
    }
}
