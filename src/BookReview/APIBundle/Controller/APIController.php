<?php
/**
 * Created by PhpStorm.
 * User: darlington
 * Date: 09/03/17
 * Time: 20:17
 */

namespace BookReview\APIBundle\Controller;


use BookReview\ReviewBundle\Entity\Review;
use BookReview\ReviewBundle\Form\APIBookType;
use BookReview\ReviewBundle\Form\ReviewType;
use FOS\RestBundle\Controller\FOSRestController;
use BookReview\ReviewBundle\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class APIController extends FOSRestController
{

    public function getBooksAction(Request $request)
    {
        self::checkAuth();

        $em = $this->getDoctrine()->getManager();

        parse_str($request->getQueryString(), $query_arr);
        if (isset($query_arr['page'])) {
            unset($query_arr['page']);
        }

        if (!$request) {
            $books = $em->getRepository('BookReviewReviewBundle:Book')->findAll();
        } else {
            $books = $em->getRepository('BookReviewReviewBundle:Book')->findBy($query_arr);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            20
        );

        return $this->handleView($this->view($pagination));
    }

    public function getBookAction($bookId)
    {
        self::checkAuth();

        if (false === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($bookId);

        if(!$book) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($book, 200);
        }
        return $this->handleView($view);
    }

    public function postBooksAction(Request $request)
    {
        self::checkAuth();

        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view("JSON format required", 400));
        }

        $book = new Book();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(APIBookType::class, $book);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {

            $foundBook13 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn13' => $book->getIsbn13()]);
            $foundBook10 = $em->getRepository('BookReviewReviewBundle:Book')->findOneBy(['isbn10' => $book->getIsbn10()]);

            if ($foundBook13 != null) {
                return $this->handleView($this->view("Book Already Exists", 412)
                    ->setLocation(
                        $this->generateUrl('api_book_get_book',
                            ['id' => $foundBook13->getId()]
                        )
                    )
                );
            } else if ($foundBook10 != null) {

                return $this->handleView($this->view("Book Already Exists", 412)
                    ->setLocation(
                        $this->generateUrl('api_book_get_book',
                            ['id' => $foundBook10->getId()]
                        )
                    )
                );
            } else {

                $details = $this->get('book_details');
                $details->init($book->getIsbn10());

                if (!$details->check()) {
                    return $this->handleView($this->view($form, 400));
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

                    return $this->handleView($this->view("Created", 201)
                        ->setLocation(
                            $this->generateUrl('book_view',
                                ['id' => $book->getId()]
                            )
                        )
                    );
                }
            }
        } else {
            return $this->handleView($this->view($form, 400));
        }
    }

    public function putBooksAction(Request $request, $bookId)
    {
        self::checkAuth();

        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view("JSON format required", 400));
        }

        $book = new Book();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(APIBookType::class, $book);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {

            $current_book = $em->getRepository('BookReviewReviewBundle:Book')->find($bookId);
            if (!$current_book) {
                return $this->handleView($this->view($form, 404));
            }
            $book->setSmallThumbnail($current_book->getSmallThumbnail());
            $book->setTimestamp($current_book->getTimestamp());
            $book->setPublishDate($current_book->getPublishDate());
            $book->setSubtitle($current_book->getSubtitle());

            $em->persist($book);
            $em->flush();

            return $this->handleView($this->view(null, 200)
                ->setLocation(
                    $this->generateUrl('book_view',
                        ['id' => $book->getId()]
                    )
                )
            );

        } else {
            return $this->handleView($this->view($form, 400));
        }
    }

    public function deleteBookAction($bookId)
    {
        self::checkAuth();

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($bookId);

        if (!$book) {
            return $this->handleView($this->view("Not Found", 404));
        } else if ($book->getAddedBy() != $this->getUser()) {
            return $this->handleView($this->view("Unauthorised", 401));
        } else {
            $em->remove($book);
            $em->flush();
            return $this->handleView($this->view("Deleted", 204));
        }
    }

    public function getReviewsAction(Request $request)
    {
        self::checkAuth();


        $em = $this->getDoctrine()->getManager();

        parse_str($request->getQueryString(), $query_arr);
        if (isset($query_arr['page'])) {
            unset($query_arr['page']);
        }

        if (!$request) {
            $reviews = $em->getRepository('BookReviewReviewBundle:Review')->findAll();
        } else {
            $reviews = $em->getRepository('BookReviewReviewBundle:Review')->findBy($query_arr);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $reviews,
            $request->query->getInt('page', 1),
            20
        );

        return $this->handleView($this->view($pagination));
    }

    public function deleteReviewsAction($reviewId)
    {
        self::checkAuth();

        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BookReviewReviewBundle:Review')->find($reviewId);

        if (!$review) {
            return $this->handleView($this->view("Not found", 404));
        } else if ($review->getReviewer() != $this->getUser()) {
            return $this->handleView($this->view("Unauthorised", 401));
        } else {
            $em->remove($review);
            $em->flush();
            return $this->handleView($this->view("Deleted", 204));
        }
    }

    public function getBooksReviewsAction(Request $request, $bookId) {
        self::checkAuth();

        parse_str($request->getQueryString(), $query_arr);
        if (isset($query_arr['page'])) {
            unset($query_arr['page']);
        }

        $query_arr['book'] = $bookId;

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($bookId);
        if(!$book) {
            $view = $this->view("Book not found", 404);
        } else {

            $reviews = $em->getRepository('BookReviewReviewBundle:Review')->findBy($query_arr);
            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $reviews,
                $request->query->getInt('page', 1),
                20
            );
            $view = $this->view($pagination, 200);
        }
        return $this->handleView($view);
    }

    public function postBooksReviewsAction(Request $request, $bookId)
    {
        self::checkAuth();

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewReviewBundle:Book')->find($bookId);
        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);

        if($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }

        $form->submit(json_decode($request->getContent(), true));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $review->setReviewer($this->getUser());
            $review->setBook($book);
            $review->setTimestamp(new \DateTime());
            $em->persist($review);
            $book->addReview($review);
            $em->flush();

            return $this->handleView($this->view("Created", 201)
                ->setLocation(
                    $this->generateUrl('review_view',
                        [
                            'id' => $review->getId()
                        ]
                    )
                )
            );
        } else {
            return $this->handleView($this->view($form, 304));
        }
    }

    public function putReviewsAction(Request $request, $reviewId)
    {
        self::checkAuth();

        if ($request->getContentType() != 'json') {
            return $this->handleView($this->view("JSON format required", 400));
        }

        $review = new Review();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ReviewType::class, $review);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {

            $rev = $em->getRepository('BookReviewReviewBundle:Review')->find($reviewId);


            if (!$rev) {
                return $this->handleView($this->view($form, 404));
            } else if ($rev->getReviewer() != $this->getUser()) {
                return $this->handleView($this->view("Forbidden", 404));
            }

            $review->setReviewer($rev->getReviewer());
            $review->setTimestamp($rev->getTimestamp());
            $em->persist($review);
            $em->flush();

            return $this->handleView($this->view(null, 200)
                ->setLocation(
                    $this->generateUrl('review_view',
                        [
                            'id' => $review->getId()
                        ]
                    )
                )
            );

        } else {
            return $this->handleView($this->view($form, 400));
        }
    }

    public function getUsersAction(Request $request)
    {
        self::checkAuth();

        parse_str($request->getQueryString(), $query_arr);
        if (isset($query_arr['page'])) {
            unset($query_arr['page']);
        }

        $em = $this->getDoctrine()->getManager();

        if (!$request) {
            $users = $em->getRepository('BookReviewUserBundle:User')->findAll();

        } else {
            $users = $em->getRepository('BookReviewUserBundle:User')->findBy($query_arr);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            20
        );
        $view = $this->view($pagination, 200);
        return $this->handleView($view);
    }

    public function getUsersBooksActions(Request $request, $userId)
    {
        self::checkAuth();

        parse_str($request->getQueryString(), $query_arr);
        if (isset($query_arr['page'])) {
            unset($query_arr['page']);
        }
        $query_arr["added_by"] = $userId;

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BookReviewUserBundle:User')->find($userId);

        if(!$user) {
            $view = $this->view("User not found", 404);
        } else {
            $books = $em->getRepository('BookReviewReviewBundle:Book')->findBy($query_arr);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $books,
            $request->query->getInt('page', 1),
            20
        );
        $view = $this->view($pagination, 200);
        return $this->handleView($view);
    }

    public function getUsersReviewsAction (Request $request, $userId)
    {
        self::checkAuth();

        parse_str($request->getQueryString(), $query_arr);
        if (isset($query_arr['page'])) {
            unset($query_arr['page']);
        }
        $query_arr["reviewer"] = $userId;

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BookReviewUserBundle:User')->find($userId);

        if(!$user) {
            $view = $this->view("User not found", 404);
        } else {
            $reviews = $em->getRepository('BookReviewReviewBundle:Review')->findBy($query_arr);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $reviews,
            $request->query->getInt('page', 1),
            20
        );
        $view = $this->view($pagination, 200);
        return $this->handleView($view);
    }

    private function checkAuth()
    {
        if (false === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }
}