<?php

namespace App\Controller;

use App\Entity\Orchid;
use App\Entity\Watering;
use App\Form\OrchidType;
use App\Service\WateringCycleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrchidsController extends AbstractController
{
    #[Route('/orchids', name: 'app_orchids_index')]
    public function index(
        EntityManagerInterface $entityManager,
        WateringCycleService $wateringCycleService,
    ): Response {
        $orchids = $entityManager
            ->getRepository(Orchid::class)
            ->findAll();

        $nextWaterings = [];
        $lastWaterings = [];

        $now = new \DateTimeImmutable();

        foreach ($orchids as $orchid) {

            $lastWatering = $orchid->getWaterings()->first();

            if ($lastWatering) {
                $wateredAt = $lastWatering->getWateredAt();

                $interval = $wateredAt->diff($now);
                $days = $interval->days;

                if ($days === 0) {
                    $lastWaterings[$orchid->getId()] = "Aujourd'hui";
                } elseif ($days === 1) {
                    $lastWaterings[$orchid->getId()] = 'Il y a 1 jour';
                } else {
                    $lastWaterings[$orchid->getId()] = 'Il y a ' . $days . ' jours';
                }
            }

            $nextWaterings[$orchid->getId()] = [
                'step' => $wateringCycleService->getNextStep($orchid),
                'type' => $wateringCycleService->getNextType($orchid),
            ];
        }

        return $this->render('orchids/index.html.twig', [
            'controller_name' => 'Liste des orchidées',
            'orchids' => $orchids,
            'nextWaterings' => $nextWaterings,
            'lastWaterings' => $lastWaterings,
        ]);
    }

    #[Route('/orchids/create/{orchid}', name: 'app_orchid_create', methods: ['GET', 'POST'])]
    public function setOrchid(
        Request $request,
        EntityManagerInterface $entityManager,
        ?Orchid $orchid = null,
    ): Response {
        if (!$orchid) {
            $orchid = new Orchid();
        }

        $form = $this->createForm(OrchidType::class, $orchid);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($orchid);
            $entityManager->flush();

            return $this->redirectToRoute('app_orchids_index');
        }

        return $this->render('orchids/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/orchid/{id}/delete', name: 'app_orchid_delete', methods: ['POST'])]
    public function deleteOrchid(
        Orchid $orchid,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($this->isCsrfTokenValid(
            'delete-' . $orchid->getId(),
            $request->request->get('_token')
        )) {
            $entityManager->remove($orchid);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_orchids_index');
    }

    #[Route('/orchid/{id}/water', name: 'app_orchid_water', methods: ['POST'])]
    public function water(
        Orchid $orchid,
        Request $request,
        WateringCycleService $cycleService,
        EntityManagerInterface $entityManager,
    ): Response {
        if (!$this->isCsrfTokenValid(
            'water-' . $orchid->getId(),
            $request->request->get('_token')
        )) {
            return $this->redirectToRoute('app_orchids_index');
        }

        $step = $cycleService->getNextStep($orchid);
        $type = $cycleService->getNextType($orchid);

        $watering = new Watering();

        $watering->setOrchid($orchid);
        $watering->setCycleStep($step);
        $watering->setType($type);
        $watering->setWateredAt(new \DateTimeImmutable());

        $entityManager->persist($watering);
        $entityManager->flush();

        return $this->redirectToRoute('app_orchids_index');
    }
}
