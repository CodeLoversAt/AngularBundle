<?php
/**
 * @package AngularBundle
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 26.11.14
 * @time 16:11
 */

namespace CodeLovers\AngularBundle\Events;


use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;

class LoginEvent extends Event
{
    /**
     * @var array
     */
    private $output;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param array $output
     *
     * @return LoginEvent
     */
    public function setOutput(array $output = null)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
} 