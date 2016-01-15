<?php

namespace SilexFwk\Test\Parser;

use SilexFwk\Parser\PostParserUtils;

class PostParserUtilTest extends \PHPUnit_Framework_TestCase
{

    public function testGetPosts()
    {
        $html = file_get_contents(__DIR__.'/../../tests-resources/index.html');
        $posts = PostParserUtils::getPosts($html);
        // there is 13 post in the input file
        assert(13 === count($posts));
    }
}