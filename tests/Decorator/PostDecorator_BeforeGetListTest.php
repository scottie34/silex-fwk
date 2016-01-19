<?php

namespace SilexFwk\Test\Decorator;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use SilexFwk\Decorator\PostDecorator;
use Symfony\Component\HttpFoundation\Request;

class PostDecorator_BeforeGetListTest extends \PHPUnit_Framework_TestCase
{
    const SELECT_QUERY_WITH_PARAMS = "SELECT o.* FROM posts o WHERE (author LIKE ?) AND (o.created BETWEEN ? AND ?)";
    const SELECT_QUERY_WITHOUT_PARAMS = "SELECT o.* FROM posts o";

    /**
     * @var PostDecorator
     */
    private $postDecorator;

    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;


    public function setUp()
    {
        parent::setUp();
        $this->postDecorator = new PostDecorator();
        $this->conn = $this->getMock('Doctrine\DBAL\Connection', array(), array(), '', false);
        $expressionBuilder = new ExpressionBuilder($this->conn);
        $this->conn->expects($this->any())
            ->method('getExpressionBuilder')
            ->will($this->returnValue($expressionBuilder));

        $this->queryBuilder = new QueryBuilder($this->conn);
        $this->queryBuilder
            ->select('o.*')
            ->from('posts', 'o');
    }


    public static function beforeGetListHarness() {
        return array(
            // Query with all params set
            array(
                array(
                    "from" => "2014-01-01",
                    "to" => "2015-01-01",
                    "author" => "Foo"
                ),
                self::SELECT_QUERY_WITH_PARAMS,
                array(
                    0 => "Foo",
                    1 => "2014-01-01",
                    2 => "2015-01-01"
                ),
            ),
            // Query without params
            array(
                array(),
                self::SELECT_QUERY_WITHOUT_PARAMS,
                array(),
            ),
            array(
                array(
                    "from" => "2014-01-01"
                ),
                self::SELECT_QUERY_WITH_PARAMS,
                array(
                    0 => PostDecorator::DEFAULT_AUTHOR,
                    1 => "2014-01-01",
                    2 => PostDecorator::DEFAULT_TO
                ),
            ),
            // Query with only 'author' params set
            array(
                array(
                    "author" => "Foo"
                ),
                self::SELECT_QUERY_WITH_PARAMS,
                array(
                    0 => "Foo",
                    1 => PostDecorator::DEFAULT_FROM,
                    2 => PostDecorator::DEFAULT_TO
                ),
            ),
            // Query with only 'from' and 'to' params set
            array(
                array(
                    "from" => "2014-01-01",
                    "to" => "2015-01-01"
                ),
                self::SELECT_QUERY_WITH_PARAMS,
                array(
                    0 => PostDecorator::DEFAULT_AUTHOR,
                    1 => "2014-01-01",
                    2 => "2015-01-01"
                ),
            ),
        );
    }

    /**
     * @dataProvider beforeGetListHarness
     */
    public function testBeforeGetList($query, $expectedQuery, $expectedParams)
    {
        $this->queryBuilder
            ->select('o.*')
            ->from('posts', 'o');

        $request = new Request($query);

        $queryBuilder = $this->postDecorator->beforeGetList($this->queryBuilder, $request);
        $this->assertEquals($expectedQuery, (string) $queryBuilder);
        $this->assertEquals($expectedParams, $queryBuilder->getParameters());
    }

}