<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_user_search')]
    public function search(Request $request, UserRepository $userRepository): Response
    {
        $search = $request->query->get('search', '');

        if (empty($search)) {
            return $this->redirectToRoute('app_profile');
        }

        $user = $userRepository->findOneBy(['username' => $search]);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('app_profile');
        }

        return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
    }
}