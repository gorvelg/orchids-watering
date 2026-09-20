<?php

namespace App\Controller;

use App\Entity\Orchid;
use App\Entity\Watering;
use App\Repository\OrchidRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HistoryController extends AbstractController
{
    #[Route('/history', name: 'app_history')]
    public function index(
        EntityManagerInterface $entityManager,
    ): Response
    {
        $orchids = $entityManager
            ->getRepository(Orchid::class)
            ->findBy([], ['name' => 'ASC']);

        return $this->render('history/history.html.twig', [
            'controller_name' => 'Historique',
            'orchids' => $orchids,
        ]);
    }
}
