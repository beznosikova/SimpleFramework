<?php
class Nav extends Model
{
    public function getList()
    {
        $sql = "select * from menu where 1";
        return $this->db->query($sql);
    }
    
    public function getById($id)
    {
        $id = (int) $id;
        $sql = "select * from menu where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }    

    public function save($data, $id = null)
    {
        if (!isset($data['title']) || !isset($data['url'])){
            return false;
        }

        $id = (int) $id;
        $root_id = (int) $data["root_id"];
        $title = $this->db->escape($data["title"]);
        $url = $this->db->escape($data["url"]);

        if (!$id) { // Add new record
            $sql = "insert into menu 
                                 set root_id ='{$root_id}', 
                                     title = '{$title}', 
                                     url = '{$url}'";
        } else { // Update existing record
            $sql = "update menu set root_id ='{$root_id}', 
                                     title = '{$title}', 
                                     url = '{$url}'
                                 where id = {$id}
                     ";
        }

        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $sql = "delete from menu where id = {$id}";
        return $this->db->query($sql);
    }    
}