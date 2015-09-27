<?php
/**
 * @package symfony-seed-mongo
 *
 * @author Daniel Holzmann <d@velopment.at>
 * @date 16.08.14
 * @time 17:58
 */

namespace CodeLovers\AngularBundle\Handler;


use CodeLovers\AngularBundle\Events\AuthEvents;
use CodeLovers\AngularBundle\Events\LoginEvent;
use FOS\UserBundle\Model\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $defaultRoute;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param string $defaultRoute
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator, $defaultRoute = '', EventDispatcherInterface $dispatcher)
    {
        $this->defaultRoute = $defaultRoute;
        $this->router = $router;
        $this->translator = $translator;
        $this->dispatcher = $dispatcher;
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            $output = array(
                'errors' => array(
                    $this->translator->trans('Bad credentials', array(), 'FOSUserBundle')
                )
            );

            return new JsonResponse($output, 401);
        } else {
            $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);

            return new RedirectResponse($this->router->generate('fos_user_security_login'));
        }
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            /** @var User $user */
            $user = $token->getUser();
            $roles = $token->getRoles();
            $outputRoles = array();

            foreach ($roles as $role) {
                $outputRoles[] = $this->translator->trans('roles.' . $role->getRole(), array(), 'CodeLoversAngularBundle');
            }

            $output = array(
                'id'     => $request->getSession()->getId(),
                'userId' => $user->getId(),
                'roles'  => $outputRoles,
                'email'  => $user->getEmail()
            );

            // notify listeners to allow output modification
            $event = new LoginEvent($user);
            $event->setOutput($output);

            $this->dispatcher->dispatch(AuthEvents::LOGIN_SUCCESS, $event);

            return new JsonResponse($event->getOutput());
        } else {
            if ($targetPath = $request->getSession()->get('_security.target_path')) {
                $url = $targetPath;
            } else {
                $url = $this->getDefaultUrl();
            }

            return new RedirectResponse($url);
        }
    }

    private function getDefaultUrl()
    {
        if (!$this->defaultRoute) {
            return '/';
        }

        return $this->router->generate($this->defaultRoute);
    }
}
