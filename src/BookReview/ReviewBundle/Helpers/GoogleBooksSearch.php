<?php
/**
 * Created by PhpStorm.
 * User: darlington
 * Date: 16/03/17
 * Time: 18:44
 */

namespace BookReview\ReviewBundle\Helpers;

use GuzzleHttp\Client;


class GoogleBooksSearch
{
    public function search($term) {
        $client = new Client(['base_uri' => 'https://www.googleapis.com/books/v1/']);
        $response = $client->request('GET', 'volumes?q=search+' . $term);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json['items'];
    }

}