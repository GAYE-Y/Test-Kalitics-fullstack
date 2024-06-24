<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Clocking;
use App\Form\CreateMultiClockingType;
use App\Repository\ClockingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clockings')]
class ClockingCollectionController extends
AbstractController
{

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/create', name: 'app_Clocking_create', methods: [
        'GET',
        'POST',
    ])]
    public function createClocking(
        EntityManagerInterface $entityManager,
        Request                $request,
    ): Response {
        $form = $this->createForm(CreateMultiClockingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clocking = $form->getData();

            foreach ($clocking->getClockingUser() as $user) {
                $existingClocking = $entityManager->getRepository(Clocking::class)
                    ->findExistingClocking(
                        $clocking->getDate(),
                        1000
                    );



                if ($existingClocking) {
                    $this->addFlash('error', 'Un pointage pour ce collaborateur à cette date existe déjà sur un chantier.');
                } else {
                    $entityManager->persist($clocking);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_Clocking_list');
                }
            }
        }

        $formView = $form->createView();

        return $this->render('app/Clocking/createMult.html.twig', [
            'form' => $formView,
        ]);
    }

    /**
     * @param \App\Repository\ClockingRepository $clockingRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/', name: 'app_Clocking_list', methods: ['GET'])]
    public function listClockings(ClockingRepository $clockingRepository): Response
    {
        $clockings = $clockingRepository->findAll();

        return $this->render('app/Clocking/list.html.twig', [
            'clockings' => $clockings,
        ]);
    }
}
