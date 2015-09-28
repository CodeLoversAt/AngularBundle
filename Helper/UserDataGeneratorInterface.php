<?php
/**
 * @package AngularBundle
 *
 * @author  Daniel Holzmann <d@velopment.at>
 * @date    28.09.15
 * @time    12:06
 */
namespace CodeLovers\AngularBundle\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface UserDataGeneratorInterface
{
    /**
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return array
     */
    public function getUserData(Request $request, TokenInterface $token);
}
