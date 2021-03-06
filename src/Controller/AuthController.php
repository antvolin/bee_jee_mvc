<?php

namespace Todo\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Todo\Lib\Factory\Template\TemplateAdapterInterface;
use Todo\Lib\Service\Auth\AuthService;
use Todo\Lib\Service\Auth\AuthServiceInterface;

class AuthController implements ControllerInterface
{
    private AuthServiceInterface $authService;
    private TemplateAdapterInterface $template;

    public function __construct(
        AuthServiceInterface $authService,
        TemplateAdapterInterface $template
    )
    {
        $this->authService = $authService;
        $this->template = $template;
    }

    /**
     * @return RedirectResponse|Response
     */
    public function login()
    {
        $token = $this->authService->getRequest()->get('token');

        if ('POST' !== $this->authService->getRequest()->getMethod()) {
            return new Response($this->template->render('form_login.html.twig', ['token' => $token]));
        }

        if ($response = $this->authService->login()) {
            return $response;
        }

        return new Response($this->template->render('form_login.html.twig', ['error' => AuthService::ERROR_MSG, 'token' => $token]));
    }

    public function logout(): RedirectResponse
    {
        return $this->authService->logout();
    }
}
