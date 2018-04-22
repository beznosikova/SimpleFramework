<?php
class Article extends Model
{
    // public function getList($only_analytics = false)
    // {
    //     $sql = "select * from news where 1";

    //     if ( $only_analytics ){
    //         $sql .= " and is_analytics = 1";
    //     }
    //     return $this->db->query($sql);
    // }

    public function getSections()
    {
        $sql = "select * from section where 1";

        return $this->db->query($sql);
    }

    public function getSectionListCount($sectionAlias)
    {
        $sectionAlias = $this->db->escape($sectionAlias);
        $sqlCounter = "SELECT COUNT(news.title) as cnt FROM news LEFT JOIN section on news.section = section.id WHERE section.alias = '{$sectionAlias}'";
        return $this->db->query($sqlCounter)[0]['cnt'];
    }

    public function getSectionList($sectionAlias, $limit = "")
    {
        $sectionAlias = $this->db->escape($sectionAlias);
        $sql = "SELECT news.title as title, news.alias as alias FROM news LEFT JOIN section on news.section = section.id WHERE section.alias = '{$sectionAlias}' {$limit}";
        return $this->db->query($sql);
    }   

    public function getByAlias($alias)
    {
        $alias = $this->db->escape($alias);
        $sql = "select * from news where alias = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function updateReaded($id, $readedOld)
    {
        $id = $this->db->escape($id);
        $readedOld = $this->db->escape($readedOld);
        $readed = ++$readedOld;
        $sql = "update news set readed ='{$readed}'
                                where id = {$id}
                     ";
        return $this->db->query($sql);
    }  

    public function getFilterItems()
    {
        $filter = [];
        $sql = "SELECT MAX(data) as max_data, MIN(data) as min_data FROM `news` WHERE 1";
        $filter = $this->db->query($sql)[0];
        $sql = "SELECT DISTINCT tags FROM `news` WHERE tags IS NOT NULL";
        if ($dbRes = $this->db->query($sql)){
            $filter['tags'] = [];
            foreach ($dbRes as $tags) {
               $filter['tags'] = array_merge($filter['tags'], explode(',', $tags['tags']));
            }
            $filter['tags'] = array_map(function($tag) { return trim($tag); }, $filter['tags']);
        }
        $filter['sections'] = $this->getSections();

        return $filter;
    }  


    public function getSearchListCount($getParams)
    {
        $sqlParams = $this->validationGet($getParams);
        if (empty($sqlParams))
            return 0;

        $sql = "SELECT COUNT(news.title) as cnt 
                    FROM news 
                    LEFT JOIN section on news.section = section.id 
                    WHERE ".implode(" AND ", $sqlParams);
        return $this->db->query($sql)[0]['cnt'];
    } 

    public function getSearchList($getParams, $limit = "")
    {
        $sqlParams = $this->validationGet($getParams);
        if (empty($sqlParams))
            return [];

        $sql = "SELECT news.title as title, news.alias as alias 
                    FROM news 
                    LEFT JOIN section on news.section = section.id 
                    WHERE ".implode(" AND ", $sqlParams)."
                    {$limit}";                    
        return $this->db->query($sql);
    } 

    private function validationGet($getParams)
    {
        $newGetParams = [];
        $sqlParams = [];

        if (!empty($getParams['sections'])){
            foreach ($getParams['sections'] as $section) {
                $newGetParams["sections"][] = $this->db->escape($section);
            }
            $sqlParams[] = "section.alias IN('".implode('\', \'', $newGetParams["sections"])."')";
        }

        if (!empty($getParams['date_from'])){
            if (preg_match('/\b\d{4}-\d{2}-\d{2}\b/', $getParams['date_from'])){
                $newGetParams["date_from"] = $this->db->escape($getParams['date_from']);
                $sqlParams[] = "news.data > '".$newGetParams["date_from"]."'";
            }
        }

        if (!empty($getParams['date_to'])){
            if (preg_match('/\b\d{4}-\d{2}-\d{2}\b/', $getParams['date_to'])) {
                $newGetParams["date_to"] = $this->db->escape($getParams['date_to']);
                $sqlParams[] = "news.data < '".$newGetParams["date_to"]."'";
            }

        }

        if (!empty($getParams['tags']) && !empty(implode('', $getParams['tags']))){
            foreach ($getParams['tags'] as $tag) {
                $newGetParams["tags"][] = $this->db->escape($tag);
            }
            $sqlParams[] = "news.tags REGEXP '".implode('|', $newGetParams["tags"])."'";
        }
        return $sqlParams;
    }


    public function getSectionById($id)
    {
        $id = (int) $id;
        $sql = "select * from section where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function saveSection($data, $id = null)
    {
        if (!isset($data['alias']) || !isset($data['title'])){
            return false;
        }

        $id = (int) $id;
        $alias = $this->db->escape($data["alias"]);
        $title = $this->db->escape($data["title"]);

        if (!$id) { // Add new record
            $sql = "insert into section 
                                 set alias ='{$alias}', 
                                     title = '{$title}'";
        } else { // Update existing record
            $sql = "update section set alias ='{$alias}', 
                                     title = '{$title}'
                                 where id = {$id}
                     ";
        }

        return $this->db->query($sql);
    }

    public function deleteSection($id)
    {
        $id = (int)$id;
        $sql = "delete from section where id = {$id}";
        return $this->db->query($sql);
    }
}