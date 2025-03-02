<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator, FormLoginAuthenticator $formLoginAuthenticator): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');
            
            $errors = [];
            
            // Validation de l'email
            if (empty($email)) {
                $errors[] = "L'adresse email est requise.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide.";
            } else {
                // Vérifier si l'email existe déjà
                $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($existingUser) {
                    $errors[] = "Cette adresse email est déjà utilisée.";
                }
            }
            
            // Validation du nom d'utilisateur
            if (empty($username)) {
                $errors[] = "Le nom d'utilisateur est requis.";
            } elseif (strlen($username) < 3) {
                $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
            }
            
            // Validation du mot de passe
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            } elseif ($password !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
            
            if (!empty($errors)) {
                return $this->render('home/register.html.twig', [
                    'errors' => $errors,
                    'last_username' => $username,
                    'last_email' => $email
                ]);
            }

            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword(
                $passwordHasher->hashPassword($user, $password)
            );
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $userAuthenticator->authenticateUser(
                $user,
                $formLoginAuthenticator,
                $request
            );

            return $this->redirectToRoute('app_redirect');
        }

        return $this->render('home/register.html.twig');
    }
}