<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $login;

     /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;

     /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $email;

     /**
     * @param login string 
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @param password string 
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param email string 
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): int
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}