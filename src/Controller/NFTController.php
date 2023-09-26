<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use App\Entity\NFT;
use App\Form\NFTType;
use App\Repository\NFTRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

//use Symfony\Component\Validator\Constraints\DateTime;


#[Route('/nft')]
class NFTController extends AbstractController
{

    #[Route('/', name: 'app_n_f_t_index', methods: ['GET'])]
    public function index(NFTRepository $nFTRepository): Response
    {
        $nft = $nFTRepository->findAll();
        return $this->json($nft ,200, [], ['groups' => 'nftall']);

    }

    #[Route('/new', name: 'app_n_f_t_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $token = $request->headers->get('Authorization');
        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
//        dd($jwtPayload->username);


        if (
            isset($data["name"]) !== null &&
            isset($data['pathImage']) !== null  &&
            isset($data['price'])  !== null &&
            isset($data['userId']) !== null
        ) {
            $user = $userRepository->findOneBy(['username' => $jwtPayload->username]);
            $nft = new NFT();
            $date = new DateTime();
            $nft->setName($data["name"]);
            $nft->setPathImage($data["pathImage"]);
            $nft->setDate($date);
            $nft->setPrice($data["price"]);
            $nft->setUser($user);
            dump($nft);
            $entityManager->persist($nft);
            dump($nft);
            $entityManager->flush();

            return new Response("NFT créé");
        } else {
            return new Response("Pas créé");
        }
    }

    #[Route('/{id}', name: 'app_n_f_t_show', methods: ['GET'])]
    public function show($id, NFTRepository $NFTRepository): Response
    {
       $nft = $NFTRepository->find($id);
        return $this->json($nft ,200, [], ['groups' => 'nftall']);
    }

    #[Route('/{id}/edit', name: 'app_n_f_t_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NFT $nFT, EntityManagerInterface $entityManager): Response
    {
      return new Response();


    }

    #[Route('/{id}', name: 'app_n_f_t_delete', methods: ['POST'])]
    public function delete(Request $request, NFT $nFT, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nFT->getId(), $request->request->get('_token'))) {
            $entityManager->remove($nFT);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_n_f_t_index', [], Response::HTTP_SEE_OTHER);
    }
}
