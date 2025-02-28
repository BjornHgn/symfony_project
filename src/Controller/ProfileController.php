<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Filesystem\Filesystem;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ArticleRepository $articleRepository, InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if ($request->isMethod('POST')) {
            $user->setUsername($request->request->get('username'));
            $user->setEmail($request->request->get('email'));

            if ($request->files->get('profil_picture')) {
                $file = $request->files->get('profil_picture');
                
                $oldImagePath = $user->getProfilPicture();
                if ($oldImagePath && $oldImagePath !== '/images/profile.png') {
                    $filesystem = new Filesystem();
                    $oldImageFullPath = $this->getParameter('kernel.project_dir') . '/public' . $oldImagePath;
                    if ($filesystem->exists($oldImageFullPath)) {
                        $filesystem->remove($oldImageFullPath);
                    }
                }

                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                
                $file->move($this->getParameter('images_directory'), $filename);
                
                $user->setProfilPicture('/images/' . $filename);
            }

            if ($request->request->get('current_password') && $request->request->get('new_password') && $request->request->get('confirm_password')) {
                if ($passwordHasher->isPasswordValid($user, $request->request->get('current_password'))) {
                    if ($request->request->get('new_password') === $request->request->get('confirm_password')) {
                        $user->setPassword(
                            $passwordHasher->hashPassword($user, $request->request->get('new_password'))
                        );
                    } else {
                        $this->addFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
                    }
                } else {
                    $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                }
            }

            if ($request->request->get('credit_amount')) {
                $creditAmount = (float) $request->request->get('credit_amount');
                $user->setBalance($user->getBalance() + $creditAmount);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_profile');
        }

        $articles = $articleRepository->findBy(['author' => $user]);
        $invoices = $invoiceRepository->findBy(['user' => $user]);

        return $this->render('home/profile.html.twig', [
            'user' => $user,
            'articles' => $articles,
            'invoices' => $invoices,
        ]);
    }
}