<?php

namespace App\Controller;

use App\helpers\CenterFetcher;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ShortListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche/api/simple", name="app_api_search")
     */
    public function search(NormalizerInterface $normalizer, Request $request, UserRepository $userRepo, ShortListRepository $shortListRepo, EntityManagerInterface $manager): Response
    {
        // get user
        $isAuth = $this->getUser() != null;
        if ($isAuth) {
            $user = $userRepo->findOneBy([
                "email" => $this->getUser()->getUserIdentifier()
            ]);

            $shortList = $shortListRepo->findBy([
                "userId" => $user->getId()
            ]);

            $normalizedShortlist = $normalizer->normalize($shortList, null, ["groups" => "shortlist:read"]);
        }


        // get request body



        $options = [];
        if ($content = $request->getContent()) {
            $options = json_decode($content, true);
        }

        $fetcher = new CenterFetcher();

        $data = $fetcher->fetchFactory($options["location"]);

        if (!$data) {
            return $this->json(json_encode(["message" => "pas de résultat pour cette recherche"]), 404);
        }

        return $this->json(json_encode([
            "data" => $data,
            "shortlist" => $isAuth ? $normalizedShortlist : []
        ]), 200);
    }


    /**
     * @Route("/recherche/simple", name="app_search")
     */
    public function index(): Response
    {
        return $this->render('search/index.html.twig',);
    }
}
