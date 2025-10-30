<?php

namespace src\middleware;

/**
 * Pagination - Simples e eficiente (Limit/Offset)
 */
class Pagination
{
    private $page = 1;
    private $limit = 20;
    private $total = 0;
    private $maxLimit = 100;

    public function __construct($limit = 20, $page = 1)
    {
        $this->limit = min((int) $limit, $this->maxLimit);
        $this->page = max((int) $page, 1);
    }

    public function getOffset()
    {
        return ($this->page - 1) * $this->limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setTotal($total)
    {
        $this->total = (int) $total;
    }

    public function getTotalPages()
    {
        if ($this->total === 0) return 0;
        return ceil($this->total / $this->limit);
    }

    /**
     * Montar resposta com metadados
     */
    public function response($data = [])
    {
        return [
            'data' => $data,
            'pagination' => [
                'page' => $this->page,
                'limit' => $this->limit,
                'total' => $this->total,
                'total_pages' => $this->getTotalPages(),
                'has_more' => $this->page < $this->getTotalPages(),
            ]
        ];
    }

    public static function fromQueryString()
    {
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        return new self($limit, $page);
    }
}
