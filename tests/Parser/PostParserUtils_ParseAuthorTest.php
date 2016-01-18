<?php

namespace SilexFwk\Test\Parser;

use SilexFwk\Parser\PostParserUtils;

class PostParserUtils_ParseAuthorTest extends \PHPUnit_Framework_TestCase
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

    public static function parseAuthorHarness() {
        return array(
            array("this is a string to parse - par Foo", "Foo"),
            array("- par Bar this is a string", "Bar"),
            array("this is a string to parse - par Foo this is a string", "Foo"));
    }

    /**
     *
     * @dataProvider parseAuthorHarness
     */
    public function testParseAuthor($toParse, $expectedAuthor) {
        $this->assertEquals($expectedAuthor, $this->postParserUtils->parseAuthor($toParse));
    }

    public static function parseBadAuthorHarness() {
        return array(
            array("this is a string to parse without author"),
            array("this is a string to parse without author -par Bar this is a string"));
    }

    /**
     * @dataProvider parseBadAuthorHarness
     * @expectedException Exception
     */
    public function testParseBadAuthor($toParse)
    {
        $this->postParserUtils->parseAuthor($toParse);
    }

}