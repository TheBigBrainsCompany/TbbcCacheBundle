<?php

namespace Tbbc\CacheBundle\Tests\Functional\Fixtures;

use Tbbc\CacheBundle\Annotation\Cacheable;
use Tbbc\CacheBundle\Annotation\CacheEvict;
use Tbbc\CacheBundle\Annotation\CacheUpdate;

class BookService
{
    /**
     * @Cacheable(caches="books", key="isbn")
     */
    public function getBookByIsbn($isbn)
    {
        return new Book();
    }

    /**
     * @CacheUpdate(caches="books", key="book.isbn")
     */
    public function saveBook(Book $book)
    {
        return $book;
    }

    /**
     * @CacheEvict(caches="books", key="book.isbn")
     */
    public function removeBook(Book $book)
    {

    }
}
