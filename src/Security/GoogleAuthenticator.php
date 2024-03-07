<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use App\Entity\Utilisateur;
use League\OAuth2\Client\Provider\GoogleUser;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use League\OAuth2\Client\Provider\Google;

class GoogleAuthenticator extends  OAuth2Authenticator
{


    
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;
    private $google_auth;   
    // User provider "App\Security\GoogleAuthenticator" must implement "Symfony\Component\Security\Core\User\UserProviderInterface".

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
        // $this->google_auth = $google_auth;
    }

    public function supports(Request $request): ?bool
    {
        // TODO: Implement supports() method.
         // continue ONLY if the current ROUTE matches the check ROUTE
         return $request->attributes->get('_route') === 'connect_google_check';
         
    }

    public function authenticate(Request $request): Passport
    {
        // TODO: Implement authenticate() method.

        
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);

                $email = $googleUser->getEmail();

                // have they logged in with Google before? Easy!
                $existingUser = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['googleId' => $googleUser->getId()]);

                //User doesnt exist, we create it !
                if (!$existingUser) {
                    $existingUser = new Utilisateur();
                    $existingUser->setEmail($email);
                    $existingUser->setnom("");
                    $existingUser->setprenom("email");
                    $existingUser->setUsername("");
                    $existingUser->setMdp("");
                    $existingUser->setadresse("");
                    $existingUser->setTel(0);

                    $existingUser->setdateinscription(new \DateTime('now'));
                    $existingUser->setGoogleId($googleUser->getId());
                    $existingUser->setHostedDomain($googleUser->getHostedDomain());
                    $this->entityManager->persist($existingUser);
                }
                $existingUser->setAvatar($googleUser->getAvatar());
                $this->entityManager->flush();

                return $existingUser;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // TODO: Implement onAuthenticationSuccess() method.

          // change "app_dashboard" to some route in your app
          return new RedirectResponse(
            $this->router->generate('app_home')
        );

        // or, on success, let the request continue to be handled by the controller
        //return null;



    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // TODO: Implement onAuthenticationFailure() method.


        
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);


    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }


// public function loadUserByUsername(string $username): UserInterface
//     {
//         // 1. Retrieve user data from Google OAuth API (implement your logic)
//         // Replace with your actual implementation using the access token
//         // $userData = $this->googleProvider->getResourceOwner(new AccessToken($accessToken));
//         $userData = [ // Replace with actual data from Google API
//             'email' => $username, // Assuming username is the email
//             'username' => $username, // Or generate a username based on email
//             // ... other user data (e.g., name, roles)
//         ];

//         // 2. Check if user exists in your database (if applicable)
//         // Replace with your logic for user existence check
//         $user = // Your logic to find user based on email (or generate a new user)

//         // 3. Create and return the UserInterface object
//         if (!$user) {
//             // Create a new user if not found
//             $user = new YourUserClass($userData['email'], null); // Replace with your user class
//             // ... populate other user properties
//             // Save the user to the database (if applicable)
//         }

//         return $user;
//     }








}
