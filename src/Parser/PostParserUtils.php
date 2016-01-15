<?php

namespace SilexFwk\Parser;

require_once(__DIR__ . '/../../vendor/electrolinux/phpQuery/phpQuery/phpQuery.php');

class PostParserUtils
{

    public static function getPosts($html)
    {
        \phpQuery::newDocumentHTML($html);

        return pq("div.post.article");
    }

    public static function parse($post)
    {
        $content = pq("p:eq(0)", $post);
        $dateAndAuthor = pq("div.date > div.right_part > p:eq(1)", $post)->text();

        if (!preg_match("/Le (.*) à/", $dateAndAuthor, $dateMatches)) {
            return new JsonResponse(new \Exception("Unable to parse the date in " . $dateAndAuthor));
        }
        if (!preg_match("/\- par (.*)$/", $dateAndAuthor, $authorMatches)) {
            return new JsonResponse(new \Exception("Unable to parse the author in " . $dateAndAuthor));
        }

        $date = \DateTime::createFromFormat('d/m/Y', $dateMatches[1]);

        $author = explode(" ",$authorMatches[1])[0];

        return array("content" => $content->text(),
                    "author" => $author,
                    "created" => $date->format('Y-m-d'));
    }
}
