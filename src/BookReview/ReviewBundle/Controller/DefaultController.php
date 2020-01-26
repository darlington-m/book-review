<?php

namespace BookReview\ReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('BookReviewReviewBundle:Book')->getLatest(24, 0);

        return $this->render('BookReviewReviewBundle:Default:index.html.twig', [
            'books' => $books
        ]);
    }
}
