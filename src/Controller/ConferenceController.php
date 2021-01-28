<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConferenceRepository;
use Twig\Environment;

class ConferenceController extends AbstractController
{

    private $twig;
     public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
      /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, ConferenceRepository $conferenceRepository): Response
    {
         $user = $request->query->get('user');
        //  if($name = $request->query->get('user')){
        //      // $user = sprintf('<h1> Hello %s! </h1>', htmlspecialchars($name));
        //  }
        return new Response($this->twig->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
            'user'=> $user,
        ]));
    }


    
    /**
     * @Route("/conference/{id}", name="conference")
     */
    public function show(Request $request, Conference $conference, CommentRepository $commentRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset); 
        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            ]));
    }
}
