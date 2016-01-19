<?php

namespace SilexFwk\Test\Decorator;


use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use SilexFwk\Decorator\PostDecorator;
use Symfony\Component\HttpFoundation\Request;

class PostDecoratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PostDecorator
     */
    private $postDecorator;

    public function setUp()
    {
        parent::setUp();
        $this->postDecorator = new PostDecorator();
    }

    public function testAfterGetList()
    {
        $dummyResult = array(
            0 => array(
                "id" => 1,
                "content" => "this is a content",
                "created" => "2014-01-01 00:00:00",
                "author" => "Foo"),
            1 => array(
                "id" => 2,
                "content" => "this is another content",
                "created" => "2013-01-01 00:00:00",
                "author" => "Bar"));

        $expectedResult = array(
            0 => array(
                "id" => 1,
                "content" => "this is a content",
                "date" => "2014-01-01 00:00:00",
                "author" => "Foo"),
            1 => array(
                "id" => 2,
                "content" => "this is another content",
                "date" => "2013-01-01 00:00:00",
                "author" => "Bar"));

        $nbResult = count($dummyResult);

        $result = $this->postDecorator->afterGetList($dummyResult);
        $this->assertCount(2, $result, "Provided results should now be wrapped in a array of size 2");
        $this->assertEquals($expectedResult, $result["posts"], "'posts' should be set to the provided results and 'created' dimension should now be 'date'");
        $this->assertEquals($nbResult, $result["count"], "count should be the length of provided results");
    }

    public function testAfterGetObject()
    {
        $dummyResult = array("id" => 1,
            "content" => "this is a content",
            "created" => "2014-01-01 00:00:00",
            "author" => "Foo");

        $expectedResult = array(
            "id" => 1,
            "content" => "this is a content",
            "date" => "2014-01-01 00:00:00",
            "author" => "Foo");

        $result = $this->postDecorator->afterGetObject((object)$dummyResult);
        $this->assertCount(1, $result, "Provided results should now be wrapped in a array of size 1");
        $this->assertEquals($expectedResult, $result["post"], "'post' should be set to the provided results and 'created' dimension should now be 'date'");
    }

    public function testFormat()
    {
        $results = array(
            "post" => array(
                "id" => 1,
                "content" => "this is a content",
                "date" => "2014-01-01 00:00:00",
                "author" => "Foo")
        );
        $response = $this->postDecorator->format($results);
        $this->assertInstanceOf("Symfony\Component\HttpFoundation\JsonResponse", $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains("date", $response->getContent());
        $this->assertContains("2014-01-01 00:00:00", $response->getContent());
        $this->assertContains("Foo", $response->getContent());
    }


}