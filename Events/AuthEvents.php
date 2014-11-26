<?php
/**
 * @package AngularBundle
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 26.11.14
 * @time 16:07
 */

namespace CodeLovers\AngularBundle\Events;


final class AuthEvents
{
    /**
     * this event occurs after a successful login
     * it allows a listener to modify the output data
     *
     * The event listener method receives an instance of
     * CodeLovers\AngularBundle\Events\LoginEvent
     */
    const LOGIN_SUCCESS = 'codelovers.angular.auth.login.success';
} 