<?php
class ShortLinker
{
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function createShortUrl($origUrl, $timeToLive)
    {
        if(empty($origUrl))
        {
            return json_encode(['type'=>'error', 'message'=>'No URL was pointed']);
        }

        if($this->validateUrlFormat($origUrl) == false)
        {
            return json_encode(['type'=>'error', 'message'=>'URL does not have valid format']);
        }

        if($this->verifyUrlExist($origUrl) == false)
        {
            return json_encode(['type'=>'error', 'message'=>'URL does not exists']);
        }

        if($this->verifyUrlDb($origUrl) == true)
        {
            return json_encode(['type'=>'error', 'message'=>'URL already added']);
        }

        $shortUrl = $this->createShortUrlCode();

        $timeToLive = $this->getTimeToLiveUrl($timeToLive);

        $created = strtotime('now');

        $lastInsertId = $this->saveDataToDb($origUrl, $shortUrl, $timeToLive, $created);

        if($lastInsertId)
        {
          return json_encode([
            'type'    =>  'success',
            'message' =>  'Your URL was added successfully',
            'id'      =>  $lastInsertId,
            'shorturl'=>  BASE_URL . $shortUrl,
            'ttl'     =>  ($timeToLive == 9999999999) ? 'permanent' : date('j M, Y', $timeToLive),
            'created' =>  date('j M, Y', $created)
          ]);
        }
        else
        {
          return json_encode(['type'=>'error', 'message'=>'Server Error. Please try latter']);
        }
    }

    private function validateUrlFormat($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }

    private function verifyUrlExist($url)
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        if(($httpCode == 404) OR ($httpCode == 0))
        {
          return FALSE;
        }
        
        return TRUE;
    }

    private function verifyUrlDb($url, $www = '')
    {
        $urlProtocol = parse_url($url, PHP_URL_SCHEME);

        // truncate form column value HTTP(s)://(www.)
        $sql = "SELECT id FROM url WHERE SUBSTRING_INDEX(orig_url, '".$urlProtocol."://".$www."', -1) = :orig_url LIMIT 1";
        $query = $this->db->prepare($sql);
        $params = ['orig_url' => $this->pureUrl($url)];
        $query->execute($params);
        $result = $query->fetch();

        if($result === FALSE AND empty($www)) // if not in DB then check with WWW
        {
          return $this->verifyUrlDb($url, $www = 'www.');
        }

        if($result === FALSE)
          return FALSE;

        return TRUE;
    }

    public function verifyShortUrlDb($url)
    {
        $sql = "SELECT id FROM url WHERE short_url = :short_url LIMIT 1";
        $query = $this->db->prepare($sql);
        $params = ['short_url' => $url];
        $query->execute($params);
        $result = $query->fetch();

        return ($result) ? TRUE : FALSE;
    }

    // remove HTTP(s)://(www.) from given url
    private function pureUrl($url)
    {
       if(substr($url, 0, 8) == 'https://')
       {
          $url = substr($url, 8);
       }

       if(substr($url, 0, 7) == 'http://')
       {
          $url = substr($url, 7);
       }
       
       if(substr($url, 0, 6) == 'ftp://')
       {
          $url = substr($url, 6);
       }

       if(substr($url, 0, 4) == 'www.')
       {
          $url = substr($url, 4);
       }
       return $url;
    }

    private function createShortUrlCode()
    {
        $charsToUrl = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $lengthToUrl = rand(5, 10);

        $shortUrl = substr(str_shuffle($charsToUrl), 0, $lengthToUrl);
        
        if($this->verifyShortUrlDb($shortUrl) == true)
        {
            return $this->createShortUrlCode();
        }

        return $shortUrl;
    }

    private function getTimeToLiveUrl($ttl)
    {
        switch($ttl)
        {
          case 'week':
            $_ttl=strtotime("+1 week");
            break;
          case 'month':
            $_ttl=strtotime("+1 month");
            break;
          case 'year':
            $_ttl=strtotime("+1 year");
            break;
          default:
            $_ttl=9999999999;
            break;
        }

        return $_ttl;
    }

    private function saveDataToDb($origUrl, $shortUrl, $timeToLive, $created)
    {
        try
        {
          $sql = "INSERT INTO url (orig_url, short_url, ttl, created) VALUES (:orig_url, :short_url, :ttl, :created)";
          $query = $this->db->prepare($sql);
          $query->bindParam(':orig_url',$origUrl);
          $query->bindParam(':short_url',$shortUrl);
          $query->bindParam(':ttl',$timeToLive);
          $query->bindParam(':created',$created);
          if($query->execute())
          {
            return $this->db->lastInsertId('id');
          }          
        }
        catch(Exception $e)
        {
          return FALSE;
        }
    }

    public function getUrlToTable()
    {
      $sql = "SELECT * FROM url WHERE ttl > ".time('now');
      $query = $this->db->query($sql);
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      if($result)
        return $result;

      return FALSE;
    }
}
