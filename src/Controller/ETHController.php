<?php

namespace App\Controller;

use App\Entity\ETH;
use App\Form\ETHType;
use App\Repository\ETHRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/e/t/h')]
class ETHController extends AbstractController
{
    #[Route('/', name: 'app_e_t_h_index', methods: ['GET'])]
    public function index(ETHRepository $eTHRepository): Response
    {
        return $this->render('eth/index.html.twig', [
            'e_t_hs' => $eTHRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_e_t_h_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $eTH = new ETH();
        $form = $this->createForm(ETHType::class, $eTH);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eTH);
            $entityManager->flush();

            return $this->redirectToRoute('app_e_t_h_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('eth/new.html.twig', [
            'e_t_h' => $eTH,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_e_t_h_show', methods: ['GET'])]
    public function show(ETH $eTH): Response
    {
        return $this->render('eth/show.html.twig', [
            'e_t_h' => $eTH,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_e_t_h_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ETH $eTH, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ETHType::class, $eTH);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_e_t_h_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('eth/edit.html.twig', [
            'e_t_h' => $eTH,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_e_t_h_delete', methods: ['POST'])]
    public function delete(Request $request, ETH $eTH, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eTH->getId(), $request->request->get('_token'))) {
            $entityManager->remove($eTH);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_e_t_h_index', [], Response::HTTP_SEE_OTHER);
    }
}
