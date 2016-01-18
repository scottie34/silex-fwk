<?php

namespace SilexFwk\Controller;


use Doctrine\DBAL\Connection;
use SilexFwk\Parser\CurlUtils;
use SilexFwk\Parser\PostParserUtils;
use Marmelab\Microrest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

require_once(__DIR__ . '/../../vendor/electrolinux/phpQuery/phpQuery/phpQuery.php');


class PostController
{
    const MODEL = 'posts';

    const BASE_URL = "http://www.viedemerde.fr";

    const NB_POSTS = 200;

    /**
     * @var Connection
     */
    protected $dbal;

    /**
     * @var CurlUtils
     */
    protected $curlUtils;

    /**
     * @var PostParserUtils
     */
    protected $postParserUtils;

    /**
     * PostController constructor.
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->dbal = $db;
        $this->curlUtils = new CurlUtils();
        $this->postParserUtils = new PostParserUtils();
    }

    /**
     * @param $currentPageIndex
     * @return string
     */
    public static function getUrl($currentPageIndex)
    {
        if ($currentPageIndex === 0) {
            return self::BASE_URL;
        }
        return self::BASE_URL . "?page=" . $currentPageIndex;
    }

    /**
     * @return JsonResponse
     */
    public function collect()
    {
        $toSave = array();
        $count = 0;
        $currentPageIndex = 0;
        while ($count < self::NB_POSTS) {
            $url = self::getUrl($currentPageIndex++);
            try {
                $html = $this->curlUtils->getResource($url);

                $posts = $this->postParserUtils->getPosts($html);

                foreach ($posts as $post) {
                    if ($count === self::NB_POSTS) {
                        break;
                    }

                    $postContent = $this->postParserUtils->parse($post);
                    $toSave[$count++] = $postContent;
                }
            } catch (\Exception $exception) {
                return new JsonResponse($exception);
            }
        }

        for ($i = 0; $i < count($toSave); $i++) {
            $this->dbal->insert(self::MODEL, $toSave[$i]);
        }

        $toReturn = count($toSave) . " posts have been added";
        return new JsonResponse($toReturn);
    }

    /**
     * For unit-tests purposes ONLY
     * @param CurlUtils $curlUtils
     */
    public function setCurlUtils($curlUtils)
    {
        $this->curlUtils = $curlUtils;
    }

    /**
     * For unit-tests purposes ONLY
     * @param PostParserUtils $postParserUtils
     */
    public function setPostParserUtils($postParserUtils)
    {
        $this->postParserUtils = $postParserUtils;
    }


}