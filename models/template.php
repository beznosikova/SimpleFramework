<?php
class Template extends Model
{
    public function getList()
    {
        $sql = "select * from template where 1";
        return $this->db->query($sql);
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