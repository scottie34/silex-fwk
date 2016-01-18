<?php

namespace SilexFwk\Test\Parser;

use SilexFwk\Parser\PostParserUtils;

class PostParserUtil_ParseTimeTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @var PostParserUtils
     */
    protected $postParserUtils;


    public function setUp()
    {
        parent::setUp();
        $this->postParserUtils = new PostParserUtils();
    }

    public static function parseTimeHarness() {
        return array(
            array("this is a string to parse 00:07", "00:07"),
            array("10:10 par Bar this is a string", "10:10"),
            array("this is a string to parse 12:38 this is a string", "12:38"));
    }

    /**
     *
     * @dataProvider parseTimeHarness
     */
    public function testParseTime($toParse, $expectedDate) {
        $this->assertEquals($expectedDate, $this->postParserUtils->parseTime($toParse));
    }

    public static function parseBadTimeHarness() {
        return array(
            array("this is a string to parse without time"),
            array("this is a string to parse with bad time 1:38"),
            array("this is another string to parse 12:75 with bad time"),);
    }

    /**
     * @dataProvider parseBadTimeHarness
     * @expectedException Exception
     */
    public function testParseBadTime($toParse)
    {
        $this->postParserUtils->parseTime($toParse);
    }

}