<?php

namespace App\Security;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;  
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface; // Ajout de l'importation manquante
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use Doctrine\ORM\EntityManagerInterface;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator implements AuthenticationEntryPointInterface 
{
         use TargetPathTrait;
         private $csrfTokenManager;
         private $entityManager;
         private $tokenStorage; // Ajout de la propriété $tokenStorage
         private $session; // Ajout de la propriété $session

    public const LOGIN_ROUTE = 'app_login'; //nom de route de login

    public function __construct(private UrlGeneratorInterface $urlGenerator,CsrfTokenManagerInterface $csrfTokenManager, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage,SessionInterface $session) //genere url
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage; // Injection de $tokenStorage
        $this->session = $session; // Assurez-vous que la propriété $session est déclarée dans la classe

    }

    // public function authenticate(Request $request): Passport //passport element de symfony qui permet de gerer l'auth des utilisateurs fih email, paasword w token
    // {
    //     $email = $request->request->get('email', ''); //recupere email
    //     $password = $request->request->get('password', '');

        
    //     $request->getSession()->set(Security::LAST_USERNAME, $email); //insere dernier user taper dans la session
    //     return new Passport(
    //         new UserBadge($email),
    //         new PasswordCredentials($request->request->get('password', '')),
    //         [
    //             new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')), //jeton securité
    //             new RememberMeBadge(),
    //         ]
    //     );
    // }

    


    public function authenticate(Request $request): Passport //passport element de symfony qui permet de gerer l'auth des utilisateurs fih email, paasword w token
    {
        $email = $request->request->get('email', ''); //requpere email
        $password = $request->request->get('password', '');

        // $userRepository = $this->entityManager->getRepository(Utilisateur::class);
        // $user = $userRepository->findOneBy(['email' => $email]);
        $userRepository = $this->entityManager->getRepository(Utilisateur::class);
        $user = $userRepository->findOneBy(['email' => $email]);
    
        if (!$user || !$user->getIsActive()) {
            throw new CustomUserMessageAuthenticationException('Your account is blocked. Please contact the administrator.');
        }
        $request->getSession()->set(Security::LAST_USERNAME, $email); //insere dernier user taper dans la session
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')), //jeton securité
                new RememberMeBadge(),
            ]
        );
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName,): ?Response
    {
 
    $user = $token->getUser();

    if ($user instanceof Utilisateur ) {
        if ($user->getIsActive()) {
            // Utilisateur actif, procéder comme d'habitude
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_list_user'));
            } elseif (in_array('ROLE_USER', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
            }
        } else {
           // Blocked user, log out and redirect to login page
           $this->tokenStorage->setToken(null);
           $request->getSession()->invalidate();
           
           return new RedirectResponse($this->urlGenerator->generate('app_login'));  // Redirect to login page
        }
    }
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        } //traj3ek win konet ekher mara connectit 

        // For example:
         return new RedirectResponse($this->urlGenerator->generate('app_home')); //yhezek lel home ki auth tet3ada s7i7a
        //throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}