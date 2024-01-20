<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\TwoFactorAuthenticationType;
use App\Security\AuthorizationServerAuthenticator;
use App\Service\CredentialsGenerator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\UnencryptedToken;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/admin/authentication/2fa/enable', name: 'app_2fa_enable')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function enable2fa(
        TotpAuthenticatorInterface $totpAuthenticator,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {

        /** @var Admin $user */
        $user = $this->getUser();
        $isEnabled = $user->isTotpAuthenticationEnabled();

        $form = $this->createForm(TwoFactorAuthenticationType::class, ['is_enabled' => $isEnabled]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @phpstan-ignore-next-line */
            $isEnabled = $form->getData()['is_enabled'];
            if ($isEnabled) {
                $user->setTotpSecret($user->getTotpSecret() ?? $totpAuthenticator->generateSecret());
            } else {
                $user->setTotpSecret(null);
            }
        }
        $entityManager->flush();

        return $this->render('admin/security/2fa_admin.html.twig', [
            'form'         => $form,
        ]);
    }

    #[Route('/admin/authentication/2fa/qr-code', name: 'app_qr_code')]
    #[IsGranted('ROLE_USER')]
    public function displayAuthenticatorQrCode(TotpAuthenticatorInterface $totpAuthenticator): Response
    {
        /** @var Admin $user */
        $user = $this->getUser();
        $qrCodeContent = $totpAuthenticator->getQRContent($user);
        $result = Builder::create()
            ->data($qrCodeContent)
            ->build();

        return new Response($result->getString(), 200, [
            'Content-Type' => 'image/png'
        ]);
    }

    #[Route('admin/api-credentials', name: 'api_credentials')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function apiCredentials(
//        CredentialsGenerator $credentialsGenerator,
//        EntityManagerInterface $entityManager
    ) {
        /** @var Admin $user */
        $user = $this->getUser();

        $apiIntegrations = $user->getApiIntegrations();
        return $this->render('admin/security/api_credentials_generate.html.twig',[
            'apiIntegrations' => $apiIntegrations
        ]);
    }

//    #[Route('/api/auth', name: 'api_auth', methods: ['POST'])]
////    public function apiAuth(Request $request, ApiAuthAuthenticator $authenticator)
//    public function apiAuth(EntityManagerInterface $manager): Response
//    {
//        /** @var Admin $user */
//        $user = $this->getUser();
//        $accessToken = $this->issueToken($user);
//        $user->setApiTokenSignature($accessToken->signature()->toString());
//        $manager->persist($user);
//        $manager->flush();
//
//
//        $data = [
//            'access_token' => $accessToken->toString(),
//        ];
//        return new JsonResponse($data, Response::HTTP_OK);
//
//    }

}
