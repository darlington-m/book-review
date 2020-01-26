<?php

namespace BookReview\ReviewBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BookReviewReviewBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
