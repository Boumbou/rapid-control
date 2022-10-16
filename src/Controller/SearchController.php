<?php

namespace App\Controller;

use CenterFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche/api/simple", name="app_api_search")
     */
    public function search(Request $request): Response
    {

        $options = [];
        if ($content = $request->getContent()) {
            $options = json_decode($content, true);
        }


        $fetcher = new CenterFetcher();

        $data = $fetcher->fetchFactory($options["location"]);

        if (!$data) {
            return $this->json(json_encode(["message" => "pas de résultat pour cette recherche"]), 404);
        }

        return $this->json($data, 200);
    }


    /**
     * @Route("/recherche/simple", name="app_search")
     */
    public function index(): Response
    {
        $fetcher = new CenterFetcher();

        $data = $fetcher->fetchDeptData(78540);

        return $this->render('search/index.html.twig', [
            "data" => $data
        ]);
    }
}
