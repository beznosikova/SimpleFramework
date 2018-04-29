<?php
class Template extends Model
{
    public function getList()
    {
        $sql = "select * from template where 1";
        return $this->db->query($sql);
    }

    public function getStyleByAlias($alias)
    {
        if (empty($alias))
            return false;
        $alias = $this->db->escape($alias);
        
        $alias = $this->db->escape($alias);
        $sql = "select * from template where alias = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0]['content'] : null;
    }    

    public function save($data)
    {
        if (!isset($data['id']) || !isset($data['content'])){
            return false;
        }

        $id = (int) $data['id'];
        $content = $this->db->escape($data["content"]);

        $sql = "update template set content ='{$content}' where id = {$id}";

        return $this->db->query($sql);
    }
}