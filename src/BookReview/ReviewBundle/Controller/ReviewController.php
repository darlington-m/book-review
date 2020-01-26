<?php

namespace BookReview\ReviewBundle\Controller;

use BookReview\ReviewBundle\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BookReview\ReviewBundle\Form\ReviewType;
use BookReview\ReviewBundle\Entity\Book;

class ReviewController extends Controller
{
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BookReviewReviewBundle:Review')->find($id);

        return $this->render('BookReviewReviewBundle:Review:view.html.twig', [
            'review' => $review,
            'book' => $review->getBook()
        ]);
    }

    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($id);
        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review,[
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $review->setReviewer($this->getUser());
            $review->setBook($book);
            $review->setTimestamp(new \DateTime());
            $em->persist($review);
            $book->addReview($review);
            $em->flush();

            $count = 0;
            $revs  = $book->getReviews();
            if ($revs != null) {
                foreach ($revs as $rev) {
                    $count += $rev->getRating();
                }
            }

            $avg = 0;

            if (count($revs) != 0 && $count != 0) {
                $avg = round($count/count($revs));
            }
            
            return $this->render('BookReviewReviewBundle:Book:view.html.twig', [
                'book' => $book,
                'reviews' => $book->getReviews(),
                'avg' => $avg
            ]);
        }
        return $this->render('BookReviewReviewBundle:Review:create.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BookReviewReviewBundle:Review')->find($id);

        $form = $this->createForm(ReviewType::class, $review,[
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);
        if($form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('review_view', [
                'id' => $review->getId()
            ]));
        }
        return $this->render('BookReviewReviewBundle:Review:edit.html.twig', [
            'form' => $form->createView(),
            'book' => $review->getBook(),
            'review' => $review
        ]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BookReviewReviewBundle:Review')->find($id);
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($review->getBook()->getId());
        $em->remove($review);
        $em->flush();

        return $this->render('BookReviewReviewBundle:Book:view.html.twig', [
            'book' => $book,
            'reviews' => $book->getReviews()
        ]);
    }


    public function recentReviewsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository('BookReviewReviewBundle:Review')->getLatest(6, 0);
        return $this->render('@BookReviewReview/recent_reviews.html.twig', [
            'reviews' => $reviews
        ]);
    }

    public function viewAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository('BookReviewReviewBundle:Review')->getUserReviews($this->getUser()->getId());


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $reviews,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('@BookReviewReview/Review/view_all.html.twig', [
            'books' => $reviews,
            'pagination' => $pagination
        ]);
    }
}
