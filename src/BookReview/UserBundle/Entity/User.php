<?php
/**
 * Created by PhpStorm.
 * User: darlington
 * Date: 11/11/16
 * Time: 16:19
 */

namespace BookReview\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ORM\Entity
 * @ORM\Table(name="assign_fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\ManyToMany(targetEntity="BookReview\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="assign_fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @Exclude()
     */
    protected $groups;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="BookReview\ReviewBundle\Entity\Review", mappedBy="reviewer")
     * @Exclude()
     */
    protected $reviews;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="BookReview\ReviewBundle\Entity\Book", mappedBy="added_by")
     * @Exclude()
     */
    protected $books;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /*
     * Check user role
     */
    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Add book
     *
     * @param \BookReview\ReviewBundle\Entity\Book $book
     *
     * @return User
     */
    public function addBook(\BookReview\ReviewBundle\Entity\Book $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \BookReview\ReviewBundle\Entity\Book $book
     */
    public function removeBook(\BookReview\ReviewBundle\Entity\Book $book)
    {
        $this->books->removeElement($book);
    }

    /**
     * Get books
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Add review
     *
     * @param \BookReview\ReviewBundle\Entity\Review $review
     *
     * @return User
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
}
