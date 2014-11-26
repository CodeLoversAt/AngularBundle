<?php
/**
 * @package AngularBundle
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 26.11.14
 * @time 16:11
 */

namespace CodeLovers\AngularBundle\Events;


use Symfony\Component\EventDispatcher\Event;

class LoginEvent extends Event
{
    /**
     * @var array
     */
    private $output;

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
} 