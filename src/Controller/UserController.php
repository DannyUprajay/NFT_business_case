<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user')]
class UserController extends AbstractController
{

    private PDOService $pdoService;
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->json($user, 200, [], ['groups' => 'userGroup']);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestData = json_decode($request->getContent(), true);

        $datePost = $requestData['birth'];
        $datePost = date_parse_from_format('j/n/Y', $datePost);

        $date = new \DateTime();
        $date->setDate($datePost['year'], $datePost['month'], $datePost['day']);

        if (
            $requestData['email'] !== null &&
            $requestData['firstName'] !== null &&
            $requestData['lastName'] !== null &&
            $requestData['birth'] !== null &&
            $requestData['password'] !== null &&
            $requestData['gender'] !== null &&
            $requestData['adress']['postalCode'] !== null &&
            $requestData['adress']['label'] !== null &&
            $requestData['adress']['contry'] !== null
        ) {
            $user = new User();
            $user->setEmail($requestData['email']);
            $user->setFirstName($requestData['firstName']);
            $user->setLastName($requestData['lastName']);
            $user->setBirth($date);
            $user->setGender($requestData['gender']);
            $user->setPassword($requestData['password']);

            $address = new Adress();
            $address->setLabel($requestData['adress']['label']);
            $address->setPostalCode($requestData['adress']['postalCode']);
            $address->setContry($requestData['adress']['contry']);

            $user->setAdress($address);

            $entityManager->persist($address);
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('Utilisateur créé avec succès', 201);
        } else {
            return new Response('Données manquantes', 400);
        }
    }






    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(UserRepository $userRepository, $id): Response
    {
//        return $this->render('user/show.html.twig', [
//            'user' => $user,
//        ]);
        $user = $userRepository->find($id);
        return $this->json($user, 200, [], ['groups' => 'userGroup']);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


}
