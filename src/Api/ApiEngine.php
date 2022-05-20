<?php

namespace App\Api;

use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiEngine
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Return json result
     *
     * @param $result
     * @param $group
     *
     * @return Response
     */
    public function fetchResponse($result, $group): Response
    {
        // Append pagination if paginator set
        if (Pagerfanta::class === get_class($result)) {
            $result = [
                'items' => $result,
                'pagination' => [
                    'current_page' => $result->getCurrentPage(),
                    'has_previous_page' => $result->hasPreviousPage(),
                    'has_next_page' => $result->hasNextPage(),
                    'per_page' => $result->getMaxPerPage(),
                    'total_items' => $result->getNbResults(),
                    'total_pages' => $result->getNbPages(),
                ],
            ];
        }

        $json = $this->serializer->serialize($result, 'json', ['groups' => $group]);

        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
