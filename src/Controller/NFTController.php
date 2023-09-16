<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use App\Entity\NFT;
use App\Form\NFTType;
use App\Repository\NFTRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (
            isset($data["name"]) !== null &&
            isset($data['pathImage']) !== null  &&
            isset($data['price'])  !== null &&
            isset($data['userId']) !== null
        ) {
            $nft = new NFT();
            $date = new DateTime();
            $nft->setName($data["name"]);
            $nft->setPathImage($data["pathImage"]);
            $nft->setDate($date);
            $nft->setPrice($data["price"]);
            $nft->setUser($this->getUser());
    var_dump($nft);
            $entityManager->persist($nft);
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
