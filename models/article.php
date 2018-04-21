<?php
class Article extends Model
{
    public function getList($only_analytics = false)
    {
        $sql = "select * from news where 1";

        if ( $only_analytics ){
            $sql .= " and is_analytics = 1";
        }
        return $this->db->query($sql);
    }

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

/*
    public function getById($id)
    {
        $id = (int) $id;
        $sql = "select * from pages where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function save($data, $id = null)
    {
        if (!isset($data['alias']) || !isset($data['title']) || !isset($data['content'])){
            return false;
        }

        $id = (int) $id;
        $alias = $this->db->escape($data["alias"]);
        $title = $this->db->escape($data["title"]);
        $content = $this->db->escape($data["content"]);
        $is_published = (isset($data["is_published"])) ? 1 : 0;

        if (!$id) { // Add new record
            $sql = "insert into pages 
                                 set alias ='{$alias}', 
                                     title = '{$title}', 
                                     content = '{$content}', 
                                     is_published = '{$is_published}'";
        } else { // Update existing record
            $sql = "update pages set alias ='{$alias}', 
                                     title = '{$title}', 
                                     content = '{$content}', 
                                     is_published = '{$is_published}' 
                                 where id = {$id}
                     ";
        }

        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $sql = "delete from pages where id = {$id}";
        return $this->db->query($sql);
    }*/
}