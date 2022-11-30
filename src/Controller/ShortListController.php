<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ShortList;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use App\Repository\ShortListRepository;
use App\helpers\CenterFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShortListController extends AbstractController
{
    /**
     * @Route("/shortlist/api/save", name="app_save_to_shortlist")
     */
    public function save(ShortListRepository $shortListRepo, UserInterface $user = null, Request $request, EntityManagerInterface $manager): Response
    {
        // get user
        $user = $this->getUser();

        //get request body
        $options = [];
        if ($content = $request->getContent()) {
            $options = json_decode($content, true);
        }
        $isShortlistedAlready = $shortListRepo->findOneBy([
            "centerId" => $options["center"]
        ]);

        if (!$isShortlistedAlready) {
            // create shortlist item
            $shortlistItem = new ShortList();
            $shortlistItem->setUserId($user);
            $shortlistItem->setCenterId($options["center"]);

            $manager->persist($shortlistItem);
        } else {
            $manager->remove($isShortlistedAlready);
        }


        $result = $manager->flush();

        return $this->json($result, 200);
    }


    /**
     * @Route("/shortlist/api/remove", name="app_delete_from_shortlist")
     */
    public function remove(Request $request, ShortListRepository $shortlistRepo): Response
    {
        $options = [];
        if ($content = $request->getContent()) {
            $options = json_decode($content, true);
        }


        $center = $shortlistRepo->findOneBy(
            ["centerId" => $options["id"]]
        );



        if ($center) {
            $shortlistRepo->remove($center, true);
            return $this->json(
                ["message" => "Enregistrement supprimé"],
                200
            );
        } else {
            return $this->json(
                ["message" => "Aucun enregistrement trouvé"],
                404
            );
        }
    }


    /**
     * @Route("/shortlist/", name="app_shortlist")
     */
    public function index(NormalizerInterface $normalizer, UserRepository $userRepo): Response
    {
        $idList = $userRepo->findOneBy(
            [
                "email" => $this->getUser()->getUserIdentifier()
            ]
        )->getShortLists();

        if (sizeof($idList) === 0) {
            return $this->render("short_list/index.html.twig", [
                "shortlist" => [],
                "shortlistCount" => 0
            ]);
        }
        $fetcher = new CenterFetcher();
        $normalizedIdList = $normalizer->normalize($idList, null, ["groups" => "shortlist:read"]);
        $shortList = $fetcher->fetchByIds($normalizedIdList);
        // dd($shortList);
        return $this->render("short_list/index.html.twig", [
            "shortlist" => $shortList,
            "shortlistCount" => $shortList["nhits"]
        ]);
    }
}
