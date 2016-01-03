<?php

use Doctrine\ORM\Mapping as ORM;
use Silex\Application;

/**
 * @ORM\Table(name="class_a")
 * @ORM\Entity()
 */
class ClassA
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="message", length=140)
     */
    private $message;

    public function getId()
    {
        return $this->id;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
