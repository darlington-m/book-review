<?php
/**
 * Created by PhpStorm.
 * User: darlington
 * Date: 11/11/16
 * Time: 18:52
 */

namespace BookReview\ReviewBundle\Helpers;


use GuzzleHttp\Client;

class BookDetails
{
    private $title;
    private $subtitle;
    private $authors;
    private $publisher;
    private $publish_date;
    private $description;
    private $isbn_10;
    private $isbn_13;
    private $page_count;
    private $print_type;
    private $categories;
    private $small_thumbnail;
    private $thumbnail;
    private $json;
    private $isbn;

    /**
     * BookDetails constructor.
     * @param $json
     */
    public function __construct()
    {

    }

    public function  init ($isbn) {
        $this->isbn = $isbn;
        $client = new Client(['base_uri' => 'https://www.googleapis.com/books/v1/']);
        $response = $client->request('GET', 'volumes?q=isbn:' . $this->isbn);
        $this->json = json_decode($response->getBody()->getContents(), true);
    }

    public function check () {
        if (!isset($this->json['items'][0])) {
            return false;
        } else {
            return true;
        }
    }

    public function generate() {
        $this->setTitle();
        $this->setSubtitle();
        $this->setAuthors();
        $this->setPublisher();
        $this->setPublishDate();
        $this->setDescription();
        $this->setIsbn();
        $this->setPageCount();
        $this->setPrintType();
        $this->setCategories();
        $this->setSmallThumbnail();
        $this->setThumbnail();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     */
    public function setTitle()
    {
        if (isset($this->json['items'][0]['volumeInfo']['title'])) {
            $this->title = $this->json['items'][0]['volumeInfo']['title'];
        } else {
            $this->title = "";
        }
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     *
     */
    public function setSubtitle()
    {
        if (isset($this->json['items'][0]['volumeInfo']['subtitle'])) {
            $this->subtitle = $this->json['items'][0]['volumeInfo']['subtitle'];
        } else {
            $this->subtitle = "";
        }
    }

    /**
     * @return mixed
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     *
     */
    public function setAuthors()
    {
        if (isset($this->json['items'][0]['volumeInfo']['authors'])) {
            $this->authors = implode(", ", $this->json['items'][0]['volumeInfo']['authors']);
        } else {
            $this->authors = "";
        }
    }

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     *
     */
    public function setPublisher()
    {
        if (isset($this->json['items'][0]['volumeInfo']['publisher'])) {
            $this->publisher = $this->json['items'][0]['volumeInfo']['publisher'];
        } else {
            $this->publisher = "";
        }
    }

    /**
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->publish_date;
    }

    /**
     *
     */
    public function setPublishDate()
    {
        if (isset($this->json['items'][0]['volumeInfo']['publishedDate'])) {
            $this->publish_date = new \DateTime($this->json['items'][0]['volumeInfo']['publishedDate']);
        } else {
            $this->publish_date = new \DateTime();
        }
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     */
    public function setDescription()
    {
        if (isset($this->json['items'][0]['volumeInfo']['description'])) {
            $this->description = $this->json['items'][0]['volumeInfo']['description'];
        } else {
            $this->description = "";
        }
    }

    /**
     * @return mixed
     */
    public function getIsbn10()
    {
        return $this->isbn_10;
    }

    /**
     *
     */
    public function setIsbn()
    {
        $isbns = $this->json['items'][0]['volumeInfo']['industryIdentifiers'];

        foreach ($isbns as $isbn) {

            if ($isbn['type'] == 'ISBN_13') {
                $this->isbn_13 = $isbn['identifier'];
            }

            if ($isbn['type'] == 'ISBN_10') {
                $this->isbn_10 = $isbn['identifier'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIsbn13()
    {
        return $this->isbn_13;
    }


    /**
     * @return mixed
     */
    public function getPageCount()
    {
        return $this->page_count;
    }

    /**
     *
     */
    public function setPageCount()
    {
        if (isset($this->json['items'][0]['volumeInfo']['pageCount'])) {
            $this->page_count = $this->json['items'][0]['volumeInfo']['pageCount'];
        } else {
            $this->page_count = 0;
        }
    }

    /**
     * @return mixed
     */
    public function getPrintType()
    {
        return $this->print_type;
    }

    /**
     *
     */
    public function setPrintType()
    {
        if (isset($this->json['items'][0]['volumeInfo']['printType'])) {
            $this->print_type = $this->json['items'][0]['volumeInfo']['printType'];
        } else {

            $this->print_type = "";
        }
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     *
     */
    public function setCategories()
    {
        if (isset($this->json['items'][0]['volumeInfo']['categories'])) {
            $this->categories = implode(", ", $this->json['items'][0]['volumeInfo']['categories']);
        } else {
            $this->categories = "";
        }
    }

    /**
     * @return mixed
     */
    public function getSmallThumbnail()
    {
        return $this->small_thumbnail;
    }

    /**
     *
     */
    public function setSmallThumbnail()
    {
        if (isset($this->json['items'][0]['volumeInfo']['imageLinks']['smallThumbnail'])) {
            $this->small_thumbnail = $this->json['items'][0]['volumeInfo']['imageLinks']['smallThumbnail'];
        } else {
            $this->small_thumbnail = "";
        }

    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     *
     */
    public function setThumbnail()
    {

        if (isset($this->json['items'][0]['volumeInfo']['imageLinks']['smallThumbnail'])) {
            $this->thumbnail = $this->json['items'][0]['volumeInfo']['imageLinks']['smallThumbnail'];
        } else {
            $this->thumbnail = "";
        }


    }
}