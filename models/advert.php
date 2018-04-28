<?php
class Advert extends Model
{
    public function getList()
    {
        $sql = "select * from advertising where 1";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id = (int) $id;
        $sql = "select * from advertising where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function save($data, $id = null)
    {
        if (!isset($data['title']) || !isset($data['vendor']) || !isset($data['price'])){
            return false;
        }

        $id = (int) $id;
        $title = $this->db->escape($data["title"]);
        $vendor = $this->db->escape($data["vendor"]);
        $price = (float) $data['price'];
        $cupon = $this->generateCupon();

        if (!$id) { // Add new record
            $sql = "insert into advertising 
                                 set cupon ='{$cupon}', 
                                     title = '{$title}', 
                                     vendor = '{$vendor}', 
                                     price = '{$price}'";
        } else { // Update existing record
            $sql = "update advertising set cupon ='{$cupon}', 
                                     title = '{$title}',
                                     vendor = '{$vendor}', 
                                     price = '{$price}'
                                 where id = {$id}
                     ";
        }

        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $sql = "delete from advertising where id = {$id}";
        return $this->db->query($sql);
    }

    private function generateCupon($length = 20){
      $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
      $numChars = strlen($chars);
      $string = '';
      for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
      }
      return $string;
    }    
}