<?php

namespace App\Controller;

use App\Entity\AccessToken;
use App\Entity\Admin;
use App\Entity\ApiIntegration;
use App\Form\TwoFactorAuthenticationType;
use App\Repository\AccessTokenRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;
use function Symfony\Component\Clock\now;

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

    #[Route('/api/authorize', name: 'api_auth', methods: ['POST'])]
    public function apiAuth(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, AccessTokenRepository $tokenRepository): Response
    {
        /** @var ApiIntegration $user */
        $user = $this->getUser();

        // Check if token already exists by its identifier - will make al previous tokens invalid
        $accessToken = $tokenRepository->findOneBy(['identifier' => $user->getClientId()]);

        if (empty($accessToken)) {
            $accessToken = (new AccessToken())->setIdentifier($user->getClientId());
        }

        $accessToken
            ->setIat(time())
            ->setExp(time() + 3600)
            ->setValue(base64_encode(Uuid::v4() . ':' . $user->getClientId() . ':' . Uuid::v4()));

        $responseData = [
            'access_token' => $accessToken->getValue(),
            'iat' => $accessToken->getIat(),
            'exp' => $accessToken->getExp(),
            'scopes' => $accessToken->getScopes()
        ];

        $accessToken->setValue($passwordHasher->hashPassword($user, $accessToken->getValue()));
        $manager->persist($accessToken);
        $manager->flush();

        return new JsonResponse($responseData);
    }

}
