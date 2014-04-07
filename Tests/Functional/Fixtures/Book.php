<?php

namespace Tbbc\CacheBundle\Tests\Functional\Fixtures;

class Book
{
    public $isbn;
    public $author;
    public $title;

    public function __construct($isbn = '12345', $author = 'Foo', $title = 'The best book ever')
    {
        $this->isbn = $isbn;
        $this->author = $author;
        $this->title = $title;
    }
}
