<?php
/**
 * @package AngularBundle
 *
 * @author  Daniel Holzmann <d@velopment.at>
 * @date    28.09.15
 * @time    12:03
 */

namespace CodeLovers\AngularBundle\Helper;


use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserDataGenerator implements UserDataGeneratorInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * UserDataGenerator constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getUserData(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();
        $roles = $token->getRoles();
        $outputRoles = array();

        foreach ($roles as $role) {
            $outputRoles[] = $this->translator->trans('roles.' . $role->getRole(), array(), 'CodeLoversAngularBundle');
        }

        $data = array(
            'id'     => $request->getSession()->getId(),
            'userId' => $user->getId(),
            'roles'  => $outputRoles,
            'email'  => $user->getEmail()
        );

        return $data;
    }
}
