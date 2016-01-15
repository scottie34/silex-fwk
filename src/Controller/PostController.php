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

    protected $dbal;

    public function __construct(Connection $db)
    {
        $this->dbal = $db;
    }

    private static function getUrl($currentPageIndex)
    {
        if ($currentPageIndex !== 0) {
            return self::BASE_URL;
        }
        return self::BASE_URL . "?page=" . $currentPageIndex;
    }

    public function collect(Request $request)
    {
        $toSave = array();
        $count = 0;
        $currentPageIndex = -1;
        while ($count < self::NB_POSTS) {
            $url = self::getUrl($currentPageIndex++);
            try {
                $html = CurlUtils::getResource($url);

                $posts = PostParserUtils::getPosts($html);

                foreach ($posts as $post) {
                    if ($count === self::NB_POSTS) {
                        break;
                    }

                    $postContent = PostParserUtils::parse($post);
                    $toSave[$count++] = $postContent;
                }
            } catch (\Exception $exception) {
                return new JsonResponse($exception);
            }
        }

        for ($i = 0; $i < count($toSave); ++$i) {
            $this->dbal->insert(self::MODEL, $toSave[$i]);
        }

        $toReturn = count($toSave) . ' posts have been added';
        $response = new JsonResponse($toReturn);

        return $response;
    }

}