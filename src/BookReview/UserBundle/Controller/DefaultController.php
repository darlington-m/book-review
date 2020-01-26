<?php

namespace BookReview\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BookReviewUserBundle:Default:index.html.twig');
    }
}
