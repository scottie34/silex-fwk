<?php

namespace SilexFwk\Test\Controller;


use Doctrine\DBAL\Connection;
use SilexFwk\Controller\PostController;
use SilexFwk\Parser\CurlUtils;
use SilexFwk\Parser\PostParserUtils;

class PostControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var PostController
     */
    private $postController;

    /**
     * @var CurlUtils
     */
    protected $curlUtils;

    /**
     * @var PostParserUtils
     */
    protected $postParserUtils;

    public function setUp()
    {
        parent::setUp();
        $this->conn = $this->getMock('Doctrine\DBAL\Connection', array(), array(), '', false);
        $this->postController = new PostController($this->conn);
        $this->postParserUtils = $this->getMock('SilexFwk\Parser\PostParserUtils');
        $this->curlUtils = $this->getMock('SilexFwk\Parser\CurlUtils');
        $this->postController->setCurlUtils($this->curlUtils);
        $this->postController->setPostParserUtils($this->postParserUtils);
    }

    public function testCollect()
    {
        // getPosts returns an array of size 10 => 20 loops => 20 getUrl
        $this->curlUtils->expects($this->exactly(20))
            ->method('getResource')
            ->will($this->returnValue('whatever'));


        $this->postParserUtils->expects($this->exactly(20))
            ->method('getPosts')
            ->will($this->returnValue(array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9)));

        $this->postParserUtils->expects($this->exactly(200))
            ->method('parse')
            ->will($this->returnValue(array("content" => "this is a content", "author" => "FOO", "created" => "2014-01-01 00:00:00")));

        // insert in Db should be called 200 times
        $this->conn->expects($this->exactly(200))
            ->method('insert');

        $response = $this->postController->collect();

        $this->assertInstanceOf("Symfony\Component\HttpFoundation\JsonResponse", $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains("200 posts have been added", $response->getContent());
    }
    
    public function testGetUrl()
    {
        $this->assertEquals(PostController::BASE_URL, $this->postController->getUrl(0));
        $this->assertEquals(PostController::BASE_URL . '?page=1', $this->postController->getUrl(1));
        $this->assertEquals(PostController::BASE_URL . '?page=8', $this->postController->getUrl(8));
    }


}