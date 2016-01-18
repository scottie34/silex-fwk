<?php

namespace SilexFwk\Test\Parser;

use SilexFwk\Controller\PostController;
use SilexFwk\Parser\CurlUtils;

class CurlUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurlUtils
     */
    protected $curlUtils;


    public function setUp()
    {
        parent::setUp();
        $this->curlUtils = new CurlUtils();
    }

    public function testGetResource()
    {
        $html = $this->curlUtils->getResource(PostController::BASE_URL);
        $this->assertStringStartsWith("<!DOCTYPE html>", $html);
        $this->assertContains("Vie de merde : Vos histoires de la vie quotidienne", $html);
    }

    public function testGetBadResource()
    {
        try {
            $html = $this->curlUtils->getResource(PostController::BASE_URL . '/bad');
        }
        catch (\Exception $e) {
            $this->assertEquals(0, $e->getCode());
            $this->assertContains("Curl : last received HTTP code is 404", $e->getMessage());
            return;
        }
        $this->fail();
    }
}