<?php

namespace Tbbc\CacheBundle\Tests\Functional;

use Tbbc\CacheBundle\Cache\CacheManagerInterface;
use Tbbc\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface;
use Tbbc\CacheBundle\Tests\Functional\Fixtures\Book;
use Tbbc\CacheBundle\Tests\Functional\Fixtures\BookService;

class CacheAnnotationTest extends FunctionalTestCase
{
    /**
     * @var BookService
     */
    private $bookService;

    /**
     * @var CacheManagerInterface
     */
    private $cacheManager;

    /**
     * @var KeyGeneratorInterface
     */
    private $keyGenerator;

    protected function setUp()
    {
        parent::setUp();

        $this->bookService = $this->getContainer()->get('book_service');
        $this->keyGenerator = $this->getContainer()->get('tbbc_cache.key_generator.simple_hash');
        $this->cacheManager = $this->getContainer()->get('tbbc_cache.simple_cache_manager');
    }

    public function testCacheableCachesTheValue()
    {
        $isbn = 'foobar123';
        $cacheKey = $this->keyGenerator->generateKey($isbn);

        $book = $this->bookService->getBookByIsbn($isbn);
        $cachedBook = $this->cacheManager->getCache('books')->get($cacheKey);

        $this->assertEquals($book, $cachedBook);
    }

    public function testCacheableCachesTheValueAndUsesTheCachedValueOnNextCalls()
    {
        $isbn = 'baz456';
        $cacheKey = $this->keyGenerator->generateKey($isbn);

        $book = $this->bookService->getBookByIsbn($isbn);
        $cachedBook = $this->cacheManager->getCache('books')->get($cacheKey);
        $supposedCachedBook = $this->bookService->getBookByIsbn($isbn);

        $this->assertSame($cachedBook, $supposedCachedBook);
    }

    public function testCacheUpdateCachesTheValue()
    {
        $book = new Book('foo123');
        $cacheKey = $this->keyGenerator->generateKey($book->isbn);

        $this->bookService->saveBook($book);
        $cachedBook = $this->cacheManager->getCache('books')->get($cacheKey);

        $this->assertEquals($book, $cachedBook);
    }

    public function testCacheEvictRemoveTheCachedValue()
    {
        $book = new Book('foobarbaz789');
        $cacheKey = $this->keyGenerator->generateKey($book->isbn);

        $this->bookService->saveBook($book);
        $cachedBook = $this->cacheManager->getCache('books')->get($cacheKey);
        $this->assertEquals($book, $cachedBook);

        $this->bookService->removeBook($book);
        $cachedBook = $this->cacheManager->getCache('books')->get($cacheKey);
        $this->assertNull($cachedBook);
    }

    /**
     * @depends testCacheUpdateCachesTheValue
     */
    public function testCacheEvictWithAllEntriesSetsToTrueRemoveAllTheCachedValues()
    {

        $bookISBNs = ['book1', 'book2', 'book3'];
        $cacheKeys = [];
        foreach ($bookISBNs as $isbn) {
            $book = new Book($isbn);
            $cacheKeys[] = $this->keyGenerator->generateKey($book->isbn);
            $this->bookService->saveBook($book);
        }

        $this->bookService->removeAllBooks();

        foreach ($cacheKeys as $cacheKey) {
            $thisVariableShouldBeNull = $this->cacheManager->getCache('books')->get($cacheKey);
            $this->assertNull($thisVariableShouldBeNull);
        }
    }
}
