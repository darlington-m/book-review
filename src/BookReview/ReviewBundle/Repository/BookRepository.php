<?php

namespace BookReview\ReviewBundle\Repository;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLatest($limit, $offset)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->orderBy('book.timestamp', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getRecentlyReviewed($limit, $offset)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->orderBy('book.timestamp','ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getTopRated($limit, $offset)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->orderBy('book.timestamp', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getSearch($term)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->orderBy('book.timestamp', 'DESC')
            ->where('book.description LIKE :term')
            ->orWhere('book.authors LIKE :terma')
            ->orWhere('book.categories LIKE :termi')
            ->orWhere('book.isbn10 = :isb')
            ->orWhere('book.isbn13 = :isbn')
            ->setParameter('term', '%'.$term.'%')
            ->setParameter('terma', '%'.$term.'%')
            ->setParameter('termi', '%'.$term.'%')
            ->setParameter('isb', $term)
            ->setParameter('isbn', $term)
        ;
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getByIsbn($isbn)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->where('book.isbn10 = :isb')
            ->orWhere('book.isbn13 = :isbn')
            ->setParameter('isb', $isbn)
            ->setParameter('isbn', $isbn)
            ->getFirstResult()
        ;
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getUserBooks($id)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->orderBy('book.timestamp', 'DESC')
            ->where('book.added_by = :id')
            ->setParameter('id', $id)
        ;
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
