<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Clocking;
use App\Form\CreateClockingType;
use App\Form\CreateMultiClockingType;
use App\Repository\ClockingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clockings')]
class ClockingCollectionController1 extends AbstractController
{
    #[Route('/crea', name: 'app_clocking_create', methods: ['GET', 'POST'])]
    public function createClocking(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $form = $this->createForm(CreateClockingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clocking = $form->getData();

            $existingClocking = $entityManager->getRepository(Clocking::class)
                ->findExistingClocking(
                    $clocking->getDate(),
                    $clocking->getClockingUser()->getId()
                );

            if ($existingClocking) {
                $this->addFlash('error', 'Un pointage pour ce collaborateur à cette date existe déjà sur un chantier.');
            } else {
                $entityManager->persist($clocking);
                $entityManager->flush();

                return $this->redirectToRoute('app_clocking_list');
            }
        }

        return $this->render('app/Clocking/create_single.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/crea_multi', name: 'app_clocking_create_multi', methods: ['GET', 'POST'])]
    public function createMultiClocking(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $form = $this->createForm(CreateMultiClockingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Clocking $clocking */
            $clocking = $form->getData();
            $users = $clocking->getClockingUsers();

            $error = false;
            foreach ($users as $user) {
                $existingClocking = $entityManager->getRepository(Clocking::class)
                    ->findExistingClocking($clocking->getDate(), $user->getId());

                if ($existingClocking) {
                    $this->addFlash('error', 'Un pointage pour ce collaborateur à cette date existe déjà sur un chantier.');
                    $error = true;
                    break;
                }
            }

            if (!$error) {
                foreach ($users as $user) {
                    $newClocking = new Clocking();
                    $newClocking->setClockingProject($clocking->getClockingProject());
                    $newClocking->setClockingUser($user);
                    $newClocking->setDate($clocking->getDate());
                    $newClocking->setDuration($clocking->getDuration());

                    $entityManager->persist($newClocking);
                }
                $entityManager->flush();

                return $this->redirectToRoute('app_clocking_list');
            }
        }

        return $this->render('app/Clocking/create_multi.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'app_clocking_list', methods: ['GET'])]
    public function listClockings(ClockingRepository $clockingRepository): Response
    {
        $clockings = $clockingRepository->findAll();

        return $this->render('app/Clocking/list.html.twig', [
            'clockings' => $clockings,
        ]);
    }
}
