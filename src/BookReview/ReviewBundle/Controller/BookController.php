<?php

namespace BookReview\ReviewBundle\Controller;

use BookReview\ReviewBundle\Entity\Book;
use BookReview\ReviewBundle\Form\BookType;
use BookReview\ReviewBundle\Form\ManualBookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    public function createAction(Request $request)
    {
        $book = new Book();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BookType::class, $book,[
            'action' => $request->getUri()
        ]);

        if ($request->get('google_search_term') != null) {
            $term = $request->get('google_search_term');
            $search = $this->get('google_books_search');
            $googleBooks = $search->search($term);

            return $this->render('@BookReviewReview/Book/create.html.twig', [
                'googleBooks' => $googleBooks,
                'term' => $term,
                'form' => $form->createView()
            ]);
        }

        $form->handleRequest($request);

        if($form->isValid()) {

            $details = $this->get('book_details');
            $details->init($book->getIsbn10());

            if (!$details->check()) {

                return $this->render('BookReviewReviewBundle:Book:create.html.twig', [
                    'form' => $form->createView(),
                    'error' => true
                ]);
            } else {

                $foundBook13 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn13' => $book->getIsbn10()]);
                $foundBook10 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn10' => $book->getIsbn10()]);

                if ($foundBook13 != null) {
                    return $this->render('BookReviewReviewBundle:Book:create.html.twig', [
                        'form' => $form->createView(),
                        'book' => $foundBook13
                    ]);
                } else if ($foundBook10 != null) {
                    return $this->render('BookReviewReviewBundle:Book:create.html.twig', [
                        'form' => $form->createView(),
                        'book' => $foundBook10
                    ]);
                } else {

                    $details->generate();
                    $book->setTitle($details->getTitle());
                    $book->setSubtitle($details->getSubtitle());
                    $book->setAuthors($details->getAuthors());
                    $book->setPublisher($details->getPublisher());
                    $book->setPublishDate($details->getPublishDate());
                    $book->setDescription($details->getDescription());
                    $book->setIsbn10($details->getIsbn10());
                    $book->setIsbn13($details->getIsbn13());
                    $book->setPageCount($details->getPageCount());
                    $book->setPrintType($details->getPrintType());
                    $book->setCategories($details->getCategories());
                    $book->setSmallThumbnail($details->getSmallThumbnail());
                    $book->setThumbnail($details->getThumbnail());
                    $book->setAddedBy($this->getUser());
                    $book->setTimestamp(new \DateTime());
                    $book->setManualEntry(0);

                    $em->persist($book);
                    $em->flush();

                    return $this->redirect($this->generateUrl('book_view', [
                        'id' => $book->getId(),
                        'reviews' => $book->getReviews()
                    ]));
                }
            }
        }

        return $this->render('BookReviewReviewBundle:Book:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function createManualAction(Request $request)
    {
        $book = new Book();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ManualBookType::class, $book,[
            'action' => $request->getUri()
        ]);

        $form->handleRequest($request);

        if($form->isValid()) {

            $foundBook13 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn13' => $book->getIsbn13()]);
            $foundBook10 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn10' => $book->getIsbn10()]);

            if ($foundBook13 != null) {
                return $this->render('BookReviewReviewBundle:Book:create.html.twig', [
                    'form' => $form->createView(),
                    'book' => $foundBook13
                ]);
            } else if ($foundBook10 != null) {
                return $this->render('BookReviewReviewBundle:Book:create.html.twig', [
                    'form' => $form->createView(),
                    'book' => $foundBook10
                ]);
            } else {

                $file = $book->getThumbnail();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );

                $book->setSmallThumbnail($fileName);
                $book->setThumbnail($fileName);
                $book->setAddedBy($this->getUser());
                $book->setTimestamp(new \DateTime());
                $book->setManualEntry(1);
                $em->persist($book);
                $em->flush();

                return $this->redirect($this->generateUrl('book_view', [
                    'id' => $book->getId(),
                    'reviews' => $book->getReviews()
                ]));
            }

        }

        return $this->render('BookReviewReviewBundle:Book:create_manual.html.twig', [
            'form' => $form->createView()
        ]);
    }


    public function createGoogleAction(Request $request, $isbn)
    {
        $book = new Book();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BookType::class, $book,[
            'action' => $request->getUri()
        ]);

        $details = $this->get('book_details');
        $details->init($isbn);

        if (!$details->check()) {

            return $this->redirect($this->generateUrl('book_create', [
                'form' => $form->createView(),
                'error' => true
            ]));
        } else {

            $foundBook13 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn13' => $isbn]);
            $foundBook10 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn10' => $isbn]);

            if ($foundBook13 != null) {

                return $this->redirect($this->generateUrl('book_create', [
                    'form' => $form->createView(),
                    'book' => $foundBook13
                ]));
            } else if ($foundBook10 != null) {

                return $this->redirect($this->generateUrl('book_create', [
                    'form' => $form->createView(),
                    'book' => $foundBook10
                ]));
            } else {

                $details->generate();
                $book->setTitle($details->getTitle());
                $book->setSubtitle($details->getSubtitle());
                $book->setAuthors($details->getAuthors());
                $book->setPublisher($details->getPublisher());
                $book->setPublishDate($details->getPublishDate());
                $book->setDescription($details->getDescription());
                $book->setIsbn10($details->getIsbn10());
                $book->setIsbn13($details->getIsbn13());
                $book->setPageCount($details->getPageCount());
                $book->setPrintType($details->getPrintType());
                $book->setCategories($details->getCategories());
                $book->setSmallThumbnail($details->getSmallThumbnail());
                $book->setThumbnail($details->getThumbnail());
                $book->setAddedBy($this->getUser());
                $book->setTimestamp(new \DateTime());
                $book->setManualEntry(0);

                $em->persist($book);
                $em->flush();

                return $this->redirect($this->generateUrl('book_view', [
                    'id' => $book->getId(),
                    'reviews' => $book->getReviews()
                ]));
            }
        }
    }


    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($id);

        sleep(5);

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

    public function editAction()
    {
        return $this->render('BookReviewReviewBundle:Book:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($id);
        $em->remove($book);
        $em->flush();

        return $this->redirect($this->generateUrl('book_homepage'));
    }

    public function topRatedAction()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('BookReviewReviewBundle:Book')->getTopRated(6, 0);

        return $this->render('@BookReviewReview/top_rated.html.twig', [
            'books' => $books
        ]);

    }

    public function searchAction(Request $request)
    {
        $term = $request->query->get('term');
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('BookReviewReviewBundle:Book')->getSearch($term);


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            5/*limit per page*/
        );



        return $this->render('@BookReviewReview/Book/search.html.twig', [
                'books' => $books,
                'term' => $term,
                'pagination' => $pagination
        ]);
    }

    public function search_googleAction(Request $request)
    {
        $term = $request->query->get('term');

        var_dump($term);
        die();
        $search = $this->get('google_books_search');
        $googleBooks = $search->search($term);

        return $this->render('@BookReviewReview/Book/create.html.twig', [
            'googleBooks' => $googleBooks,
            'term' => $term
        ]);
    }

    public function viewAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('BookReviewReviewBundle:Book')->getUserBooks($this->getUser()->getId());


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('@BookReviewReview/Book/view_all.html.twig', [
            'books' => $books,
            'pagination' => $pagination
        ]);
    }

}
