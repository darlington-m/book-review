<?php

namespace BookReview\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Exclude;

/**
 * Review
 *
 * @ORM\Table(name="assign_review")
 * @ORM\Entity(repositoryClass="BookReview\ReviewBundle\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @var \BookReview\ReviewBundle\Entity\Book
     * @ORM\ManyToOne(targetEntity="BookReview\ReviewBundle\Entity\Book", inversedBy="reviews")
     * @ORM\JoinColumn(name="book", referencedColumnName="id", onDelete="CASCADE")
     * @Exclude()
     */
    private $book;

    /**
     * @var \BookReview\UserBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="BookReview\UserBundle\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="reviewer", referencedColumnName="id")
     * @Exclude()
     */
    private $reviewer;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    private $body;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     * @Exclude()
     */
    private $timestamp;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Review
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Review
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Review
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set book
     *
     * @param \BookReview\ReviewBundle\Entity\Book $book
     *
     * @return Review
     */
    public function setBook(\BookReview\ReviewBundle\Entity\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \BookReview\ReviewBundle\Entity\Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set reviewer
     *
     * @param \BookReview\UserBundle\Entity\User $reviewer
     *
     * @return Review
     */
    public function setReviewer(\BookReview\UserBundle\Entity\User $reviewer = null)
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    /**
     * Get reviewer
     *
     * @return \BookReview\UserBundle\Entity\User
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }
}
