<?php

namespace SilexFwk\Decorator;


use Doctrine\DBAL\Query\QueryBuilder;
use Marmelab\Microrest\Decorator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

require_once(__DIR__ . '/../../vendor/electrolinux/phpQuery/phpQuery/phpQuery.php');


class PostDecorator extends Decorator
{
    const DEFAULT_AUTHOR = '%';
    const DEFAULT_FROM = '1970-01-01';
    const DEFAULT_TO = '2070-01-01';


    /**
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return QueryBuilder
     */
    public function beforeGetList(QueryBuilder $queryBuilder, Request $request)
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $author = $request->query->get('author');

        if (!$from && !$to && !$author) {
            return $queryBuilder;
        } else {
            return $queryBuilder
                ->where($queryBuilder->expr()->like('author', '?'))
                ->andWhere('o.created BETWEEN ? AND ?')
                ->setParameter(0, $author ? $author : self::DEFAULT_AUTHOR)
                ->setParameter(1, $from ? $from : self::DEFAULT_FROM)
                ->setParameter(2, $to ? $to : self::DEFAULT_TO);
        }
    }

    /**
     * @param $results array
     * @return array
     */
    public function afterGetList($results)
    {
        $results = $this->renameCreatedToDate($results);
        return array("posts" => $results,
            "count" => count($results));
    }

    /**
     * @param $result array
     * @return array
     */
    public function afterGetObject($result)
    {
        $result = $this->renameCreatedToDate(array(0 => get_object_vars($result)));
        return array("post" => $result[0]);
    }

    /**
     * @param $results array
     * @return JsonResponse
     */
    public function format($results)
    {
        $response = new JsonResponse($results, 200);
        $response->setEncodingOptions(JSON_PRETTY_PRINT);
        return $response;
    }

    /**
     * @param $results array
     * @return array
     */
    public function renameCreatedToDate($results)
    {
        return array_map(function ($post) {
            return array(
                'id' => $post['id'],
                'content' => $post['content'],
                'date' => $post['created'],
                'author' => $post['author'],
            );
        }, $results);
    }


}