<?php

namespace App\Controller;

use App\Entity\ShortList;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ShortListController extends AbstractController
{
    /**
     * @Route("/shortlist/api/save", name="app_save_to_shortlist")
     */
    public function save(UserInterface $user = null, Request $request, EntityManagerInterface $manager): Response
    {
        // get user
        $user = $this->getUser();

        //get request body
        $options = [];
        if ($content = $request->getContent()) {
            $options = json_decode($content, true);
        }

        // create shortlist item
        $shortlistItem = new ShortList();
        $shortlistItem->setUserId($user);
        $shortlistItem->setCenterId($options["center"]);

        $manager->persist($shortlistItem);

        $result = $manager->flush();

        return $this->json($result, 200);
    }
}
