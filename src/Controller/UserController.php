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
        var_dump($requestData);

        if( isset($requestData['email']) !== null &&
            isset($requestData['firstName']) !== null  &&
            isset($requestData['lastName']) !== null &&
            isset($requestData['username']) !== null &&
            isset($requestData['role']) !== null &&
            isset($requestData['birth']) !== null &&
            isset($requestData['password']) !== null &&
            isset($requestData['gender']) !== null &&
            isset($requestData['adress']['postalCode']) !== null &&
            isset($requestData['adress']['label']) !== null &&
            isset($requestData['adress']['contry']) !== null
        ) {

            $datePost = $requestData['birth'];
            $datePost = date_parse_from_format('j/n/Y', $datePost);
            $date = new DateTime();

            $date->setDate($datePost['year'], $datePost['month'], $datePost['day']);

            $user = new User();
            $user->setEmail($requestData['email']);
            $user->setFirstName($requestData['firstName']);
            $user->setLastName($requestData['lastName']);
            $user->setUsername($requestData['username']);
            $user->setBirth($date);
            $user->setRoles(['ROLE_USER']);
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

            if (isset($requestData['role'])) {
                $user->setRoles($requestData['role']);
            }
            return new Response();
        } else {
            return new Response();
        }
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);
        return $this->json($user, 200, [], ['groups' => 'userGroup']);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, UserRepository $user, EntityManagerInterface $entityManager): Response
    {
        $requestData = json_decode($request->getContent(), true);
        $user = $user->find($id);

        if (!$user) {
            return new Response('Utilisateur non trouvé', 404);
        }

        $userModified = false;

        if ($requestData["email"] !== $user->getEmail()) {
            $user->setEmail($requestData["email"]);
            $userModified = true;
        }

        if (isset($requestData["firstName"]) && $requestData["firstName"] !== $user->getFirstName()) {
            $user->setFirstName($requestData["firstName"]);
            $userModified = true;
        }

        if ($requestData["lastName"] !== "" && $requestData["lastName"] !== $user->getLastName()) {
            $user->setLastName($requestData["lastName"]);
            $userModified = true;
        }

        if (isset($requestData["password"]) && $requestData["password"] !== $user->getPassword()) {
            $user->setPassword($requestData["password"]);
            $userModified = true;
        }

        if (isset($requestData["adress"])) {
            $addressData = $requestData["adress"];
            $adress = $user->getAdress(); // Obtenez l'adresse de l'utilisateur

            if ($adress) {
                if (isset($addressData["label"]) && $addressData["label"] !== $adress->getLabel()) {
                    $adress->setLabel($addressData["label"]);
                    $userModified = true;
                }

                if (isset($addressData["postalCode"]) && $addressData["postalCode"] !== $adress->getPostalCode()) {
                    $adress->setPostalCode($addressData["postalCode"]);
                    $userModified = true;
                }

                if (isset($addressData["country"]) && $addressData["country"] !== $adress->getContry()) {
                    $adress->setContry($addressData["country"]);
                    $userModified = true;
                }
            }
        }

        if ($userModified) {
            $entityManager->flush();
            return new Response();
        } else {
            return new Response();
        }

    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete($id , Request $request, UserRepository $user, EntityManagerInterface $entityManager): Response
    {

        $user = $user->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return new Response();

    }

}
