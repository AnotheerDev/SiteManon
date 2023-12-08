<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);
    
        // Récupérer l'entité utilisateur en fonction de l'email
        $user = $this->userRepository->findOneByEmail($email);
    
        // Vérifier si l'utilisateur est vérifié
        if (!$user || !$user->isVerified()) {
            // Gérer l'échec de la vérification ici, par exemple, en ajoutant un message flash
            $request->getSession()->getFlashBag()->add('error', 'Votre compte n\'est pas vérifié.');
            // Redirection vers la page de login
            throw new AuthenticationException('Votre compte n\'est pas vérifié.');
        }
    
        return new Passport(
            new UserBadge($email, function($email) {
                // Assurez-vous que cette fonction retourne l'entité utilisateur
                return $this->userRepository->findOneByEmail($email);
            }),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }
    


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
    
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
    
        // Redirection par défaut si aucun chemin cible n'est défini
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
    



    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
