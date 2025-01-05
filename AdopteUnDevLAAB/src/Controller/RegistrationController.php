<?php

namespace App\Controller;

use App\Entity\Dev;
use App\Entity\User;
use App\Entity\Company;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier,private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security): Response
    {
        if ($request->getMethod() == 'POST'){

            $email      =   $request->request->get('email');
            $userExist  =   $this->entityManager->getRepository(User::class)->findOneByEmail($email);

            if ($userExist) return new JsonResponse(["status"=>"exist","msg"=>"Cet email est déjà associé à un compte existant"]);

            $user       =   new User();

            $raisonSocial = $request->request->get('name',null);

            $raisonSocial ? $this->company($request,$user) : $this->dev($request,$user);
                

            $user->setEmail($email);
           
            $user->setVerified(true);

            /** @var string $plainPassword */
            $plainPassword = $request->request->get('password');
            
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // generate a signed url and email it to the user
            // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('support@unlaab.com', 'LAAB'))
            //         ->to((string) $user->getEmail())
            //         ->subject('Please Confirm your Email')
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );

            // do anything else you need here, like send an email

         //   $security->login($user, AppCustomAuthenticator::class, 'main');

            return new JsonResponse(["status"=>"success","msg"=>"Inscription effecutée avec succès","url"=>$this->generateUrl('app_login',[],0)]);

        }

        return $this->render('registration/register.html.twig');
    }

    private function dev(Request $request,User $user) : void {

        $firstName  =   $request->request->get('firstName');
        $lastName   =   $request->request->get('lastName');
        
        $dev = new Dev();
        $dev->setNom($lastName);
        $dev->setPrenoms($firstName);
        $dev->setVisibilite(false);
        $user->setDev($dev);
        $user->setRoles(["ROLE_DEV"]);
        $this->entityManager->persist($dev);
        $this->entityManager->persist($user);

    }

    private function company(Request $request,User $user) : void {

        $company = new company();

        $company->setRaisonSociale($request->request->get('name'));
        $user->setRoles(["ROLE_COMPANY"]);
        $user->setCompany($company);

        $this->entityManager->persist($company);
        $this->entityManager->persist($user);

    }



    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
