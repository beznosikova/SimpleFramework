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
        return "LIMIT ".($this->page-1).",".(Config::get('pagination_limit'));
    }
}