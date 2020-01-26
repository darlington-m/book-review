<?php

namespace BookReview\ReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;

/**
 * Book
 *
 * @ORM\Table(name="assign_book")
 * @ORM\Entity(repositoryClass="BookReview\ReviewBundle\Repository\BookRepository")
 */
class Book
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="BookReview\ReviewBundle\Entity\Review", mappedBy="book")
     * @Exclude()
     */
    protected $reviews;


    /**
     * @var \BookReview\UserBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="BookReview\UserBundle\Entity\User", inversedBy="books")
     * @ORM\JoinColumn(name="added_by", referencedColumnName="id")
     */
    private $added_by;


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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="authors", type="string", length=255, nullable=true)
     */
    private $authors;

    /**
     * @var string
     *
     * @ORM\Column(name="publisher", type="string", length=255, nullable=true)
     */
    private $publisher;

    /**
     * @var int
     *
     * @ORM\Column(name="publish_date", type="datetime", nullable=true)
     * @Exclude()
     */
    private $publishDate;


    /**
     * @var int
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=true)
     * @Exclude()
     */
    private $timestamp;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="isbn_10", type="string", length=255)
     * @Assert\Isbn(type="null")
     */
    private $isbn10;

    /**
     * @var int
     *
     * @ORM\Column(name="isbn_13", type="string", length=255)
     * @Assert\Isbn(type = "isbn13", message = "This value is not a valid ISBN.")
     */
    private $isbn13;

    /**
     * @var int
     *
     * @ORM\Column(name="page_count", type="integer")
     */
    private $pageCount;

    /**
     * @var string
     *
     * @ORM\Column(name="print_type", type="string", length=255, nullable=true)
     */
    private $printType;

    /**
     * @var string
     *
     * @ORM\Column(name="categories", type="string", length=255, nullable=true)
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="small_thumbnail", type="string", length=255, nullable=true)
     * @Exclude()
     */
    private $smallThumbnail;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="manual_entry", type="boolean", options={"default":0})
     * @Exclude()
     */
    private $manualEntry;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * @return Book
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
     * Set subtitle
     *
     * @param string $subtitle
     *
     * @return Book
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set authors
     *
     * @param string $authors
     *
     * @return Book
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Get authors
     *
     * @return string
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     *
     * @return Book
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set publishDate
     *
     * @param integer $publishDate
     *
     * @return Book
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get publishDate
     *
     * @return int
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Book
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isbn10
     *
     * @param integer $isbn10
     *
     * @return Book
     */
    public function setIsbn10($isbn10)
    {
        $this->isbn10 = $isbn10;

        return $this;
    }

    /**
     * Get isbn10
     *
     * @return int
     */
    public function getIsbn10()
    {
        return $this->isbn10;
    }

    /**
     * Set isbn13
     *
     * @param integer $isbn13
     *
     * @return Book
     */
    public function setIsbn13($isbn13)
    {
        $this->isbn13 = $isbn13;

        return $this;
    }

    /**
     * Get isbn13
     *
     * @return int
     */
    public function getIsbn13()
    {
        return $this->isbn13;
    }

    /**
     * Set pageCount
     *
     * @param integer $pageCount
     *
     * @return Book
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    /**
     * Get pageCount
     *
     * @return int
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * Set printType
     *
     * @param string $printType
     *
     * @return Book
     */
    public function setPrintType($printType)
    {
        $this->printType = $printType;

        return $this;
    }

    /**
     * Get printType
     *
     * @return string
     */
    public function getPrintType()
    {
        return $this->printType;
    }

    /**
     * Set categories
     *
     * @param string $categories
     *
     * @return Book
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set smallThumbnail
     *
     * @param string $smallThumbnail
     *
     * @return Book
     */
    public function setSmallThumbnail($smallThumbnail)
    {
        $this->smallThumbnail = $smallThumbnail;

        return $this;
    }

    /**
     * Get smallThumbnail
     *
     * @return string
     */
    public function getSmallThumbnail()
    {
        return $this->smallThumbnail;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Book
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set addedBy
     *
     * @param \BookReview\UserBundle\Entity\User $addedBy
     *
     * @return Book
     */
    public function setAddedBy(\BookReview\UserBundle\Entity\User $addedBy = null)
    {
        $this->added_by = $addedBy;

        return $this;
    }

    /**
     * Get addedBy
     *
     * @return \BookReview\UserBundle\Entity\User
     */
    public function getAddedBy()
    {
        return $this->added_by;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Book
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
     * Add review
     *
     * @param \BookReview\ReviewBundle\Entity\Review $review
     *
     * @return Book
     */
    public function addReview(\BookReview\ReviewBundle\Entity\Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \BookReview\ReviewBundle\Entity\Review $review
     */
    public function removeReview(\BookReview\ReviewBundle\Entity\Review $review)
    {
        $this->reviews->removeElement($review);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set manualEntry
     *
     * @param boolean $manualEntry
     *
     * @return Book
     */
    public function setManualEntry($manualEntry)
    {
        $this->manualEntry = $manualEntry;

        return $this;
    }

    /**
     * Get manualEntry
     *
     * @return boolean
     */
    public function getManualEntry()
    {
        return $this->manualEntry;
    }
}
