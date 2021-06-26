<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;


    /**
     * @ORM\Column(type="string", unique=true)
     */
    public $email;


    /**
     * @ORM\Column(type="string")
     */
    public $password;

    /**
     * @ORM\Column(type="string",nullable = true)
     */
    public $token;

    /**
     * @ORM\Column(type="datetime")
     */
    public $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    public $updated_at;

}

