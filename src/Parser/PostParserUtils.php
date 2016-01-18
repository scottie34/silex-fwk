<?php

namespace SilexFwk\Parser;

require_once(__DIR__ . '/../../vendor/electrolinux/phpQuery/phpQuery/phpQuery.php');

use Symfony\Component\HttpFoundation\JsonResponse;

class PostParserUtils
{

    const DATE_REGEXP = "/([0-3][0-9])\/([0-1][0-9])\/([0-2][0-9][0-9][0-9])/";

    const TIME_REGEXP = "/([0-1][0-9]|2[0-3]):[0-5][0-9]/";

    const AUTHOR_REGEXP = "/\- par ([^ ]*)/";

    const AUTHOR_PARAM = "author";

    const DATE_PARAM = "date";

    const TIME_PARAM = "time";

    /**
     * @param $html
     * @return array|\phpQueryObject
     */
    public function getPosts($html)
    {
        \phpQuery::newDocumentHTML($html);

        return pq("div.post.article");
    }

    /**
     * @param $post \phpQueryObjec
     * @return array|JsonResponse
     */
    public function parse($post)
    {
        $content = pq("p:eq(0)", $post);
        $dateAndAuthor = pq("div.date > div.right_part > p:eq(1)", $post)->text();

        try {
            $date = self::parseDate($dateAndAuthor);
            $time = self::parseTime($dateAndAuthor);
            $author = self::parseAuthor($dateAndAuthor);


            $date = \DateTime::createFromFormat('d/m/Y G:i', $date . " " . $time);


            return array("content" => self::trimWithoutNewLines($content->text()),
                "author" => self::trimWithoutNewLines($author),
                "created" => $date->format('Y-m-d G:i:s'));
        }
        catch (\Exception $e) {
            return new JsonResponse($e);
        }
    }

    /**
     * @param $value
     * @return bool|string
     */
    public function trimWithoutNewLines($value)
    {
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * @param $toParse
     * @return mixed
     * @throws \Exception
     */
    public function parseDate($toParse)
    {
        $matches = self::parse0($toParse, self::DATE_REGEXP, self::DATE_PARAM);
        return $matches[0];
    }

    /**
     * @param $toParse
     * @return mixed
     * @throws \Exception
     */
    public function parseTime($toParse)
    {
        $matches = self::parse0($toParse, self::TIME_REGEXP, self::TIME_PARAM);
        return $matches[0];
    }

    /**
     * @param $toParse
     * @return mixed
     * @throws \Exception
     */
    public function parseAuthor($toParse)
    {
        $matches = self::parse0($toParse, self::AUTHOR_REGEXP, self::AUTHOR_PARAM);
        return $matches[1];
    }

    /**
     * @param $toParse
     * @param $regexp
     * @param $paramName
     * @return mixed
     * @throws \Exception
     */
    public function parse0($toParse, $regexp, $paramName) {
        if (!preg_match($regexp, $toParse, $matches)) {
            throw new \Exception("Unable to parse the " . $paramName . " in " . $toParse);
        }
        return $matches;
    }

}
