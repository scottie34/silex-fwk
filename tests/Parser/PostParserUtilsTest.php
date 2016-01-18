<?php

namespace SilexFwk\Test\Parser;

use SilexFwk\Parser\PostParserUtils;

class PostParserUtilsTest extends \PHPUnit_Framework_TestCase
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

    public function testGetPosts()
    {
        $html = file_get_contents(__DIR__.'/../../tests-resources/index.html');
        $posts = $this->postParserUtils->getPosts($html);
        // there is 13 post in the input file
        $this->assertCount(13, $posts, "13 posts are available in the input file");
    }

    public function testParse()
    {
        $html = file_get_contents(__DIR__.'/../../tests-resources/post.html');
        $posts = $this->postParserUtils->getPosts($html);
        $this->assertCount(1, $posts, "Only 1 post is available in the input file");
        $post = $this->postParserUtils->parse($posts[0]);

        $this->assertCount(3, $post, "parsed post should be a 3-length array");
        $this->assertEquals("This is the post content. VDM", $post['content']);
        $this->assertEquals("TestAuthor", $post['author']);
        $this->assertEquals("2016-01-13 13:33:00", $post['created']);
    }
}