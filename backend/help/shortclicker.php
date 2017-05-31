<?php
class ShortClicker
{
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getOrigUrl($id)
    {
        $sql = "SELECT orig_url FROM url WHERE id = :id LIMIT 1";
        $query = $this->db->prepare($sql);
        $params = ['id' => (int)$id];
        $query->execute($params);
        $result = $query->fetch();

        if($result)
        {
          $this->increaseClick($id);
          
          return json_encode(['type'=>'success', 'link'=>$result['orig_url']]); 
        }

        return json_encode(['type'=>'error', 'message'=>'No such URL']);
    }

    public function getOrigUrlByShortUrl($shortUrl)
    {
        $sql = "SELECT orig_url FROM url WHERE short_url = :short_url LIMIT 1";
        $query = $this->db->prepare($sql);
        $params = ['short_url' => $shortUrl];
        $query->execute($params);
        $result = $query->fetch();

        return $result['orig_url'];
    }

    private function increaseClick($id)
    {
      $sql = "UPDATE url SET click = `click`+1 WHERE id = :id";
      $query = $this->db->prepare($sql);
      $params = ['id' => (int)$id];
      $query->execute($params);

      return TRUE;
    }

}
