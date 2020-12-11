<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Picture;
use App\Repository\EventRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EventRepository $repo)
    {
        $nextEvents = $repo->findByEventDate(true);
        $previousEvents = $repo->findByEventDate(false);

        return $this->render('index/index.html.twig', [
            'nextEvents' => $nextEvents,
            'previousEvents' => $previousEvents
        ]);
    }
    /**
     * @Route("/event/{id}", name="show")
     */
    public function show(Event $event)
    {
        return $this->render('index/show.html.twig', [
            'event' => $event,
        ]);
    }
     /**
     * @Route("/picture/{id}", name="picture")
     */
    public function picture(Picture $picture)
    {
        return $this->render('index/picture.html.twig', [
            'picture' => $picture,
        ]);
    }
    /**
     * @Route("/law", name="law")
     */
    public function law()
    {
        return $this->render('index/law.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
