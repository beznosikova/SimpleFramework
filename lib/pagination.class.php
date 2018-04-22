<?php
class Pagination
{
    public $page;
    public $pages;
    public $curentPage;

    public function __construct(
        int $itemsCount, 
        string $pageUrl = ""
    ){
        $this->pages = ceil($itemsCount / Config::get('pagination_limit'));
        $this->page = $this->calcPage($pageUrl);

        if ($this->page > $this->pages)
            $this->page = 1;

        $this->curentPage = App::getRouter()->getPaginationUrl();
        if (!empty($_GET))
            $this->queryString = $_SERVER["QUERY_STRING"];
    }

    private function calcPage($pageUrl)
    {
        if (!$pageUrl)
            return 1;

        if (preg_match(Config::get('pagination_pattern'), $pageUrl, $page)){
            $page = (int)explode('-', $pageUrl)[1];
            return ($page < 2) ? 1 : $page;
        } else 
            return 1;
    }

    public function getSqlLimit()
    {
        $paginationLimit = Config::get('pagination_limit');
        return "LIMIT ".($paginationLimit*($this->page-1)).",".$paginationLimit;
    }
}