<?php

namespace SilexFwk\Test\Parser;

use SilexFwk\Parser\PostParserUtils;

class PostParserUtils_ParseDateTest extends \PHPUnit_Framework_TestCase
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

    public static function parseDateHarness() {
        return array(
            array("this is a string to parse 12/01/2013", "12/01/2013"),
            array("12/05/2015 par Bar this is a string", "12/05/2015"),
            array("this is a string to parse 31/01/2014 this is a string", "31/01/2014"));
    }

    /**
     *
     * @dataProvider parseDateHarness
     */
    public function testParseDate($toParse, $expectedDate) {
        $this->assertEquals($expectedDate, $this->postParserUtils->parseDate($toParse));
    }

    public static function parseBadDateHarness() {
        return array(
            array("this is a string to parse without date"),
            array("this is a string to parse with bad date 40/01/2015"),
            array("this is another string to parse 31/01/15 with bad date format"),);
    }

    /**
     * @dataProvider parseBadDateHarness
     * @expectedException Exception
     */
    public function testParseBadDate($toParse)
    {
        $this->postParserUtils->parseDate($toParse);
    }

}