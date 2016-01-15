<?php

namespace SilexFwk\Decorator;


use Doctrine\DBAL\Query\QueryBuilder;
use Marmelab\Microrest\Decorator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

require_once(__DIR__ . '/../../vendor/electrolinux/phpQuery/phpQuery/phpQuery.php');


class PostDecorator extends Decorator
{


    public function beforeGetList(QueryBuilder $queryBuilder, Request $request)
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');
        $author = $request->query->get('author');

        if (!$from && !$to && !$author) {
            return $queryBuilder;
        } else {
            return $queryBuilder
                ->select('o.*')
                ->from('posts', 'o')
                ->where($queryBuilder->expr()->eq('author', '?'))
                ->andWhere('o.created BETWEEN ? AND ?')
                ->setParameter(0, $author)
                ->setParameter(1, $from ? $from : '1970-01-01')
                ->setParameter(2, $to ? $to : '2070-01-01');
        }
    }

    public function afterGetList($results)
    {

        return array("posts" => $results,
                            "count" => count($results));
    }

    public function afterGetObject($result)
    {
        return array("post" => $result);
    }

    public function format($results)
    {
        $response = new JsonResponse($results, 200);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        $response->setEncodingOptions(JSON_PRETTY_PRINT);
        return $response;
    }


}