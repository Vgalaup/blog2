<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }


        return $this->renderForm('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'form' => $form
        ]);
    }
}
