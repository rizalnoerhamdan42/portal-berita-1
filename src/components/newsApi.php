<?php
namespace app\components;

use Yii;
use yii\base\Component;

class NewsApi extends Component
{
    private $baseUrl = 'https://newsapi.org/v2/';
     

   public function getTopHeadlines($category = null)
    {
        $apiKey = Yii::$app->params['newsApiKey'];
        $url = $this->baseUrl . 'top-headlines/sources?apiKey=' . $apiKey;
        if ($category) {
            $url .= '&category=' . urlencode($category);
        }

        return $this->fetch($url);
   }

  


  



    public function getEverything($query = '', $from = '', $to = '', $page = 1, $pageSize = 10)
    {

        if (empty($query)) {
            $query = 'android';
        }
        
        if (empty($from)) {
            $from = date('Y-m-d', strtotime('-1 days'));
        }
        if (empty($to)) {
            $to = date('Y-m-d');
        }

        $apiKey = Yii::$app->params['newsApiKey'];
        $url = $this->baseUrl . "everything?q={$query}&from={$from}&to={$to}&sortBy=popularity&page={$page}&pageSize={$pageSize}&apiKey={$apiKey}";

      //  return $url;
         return $this->fetch($url);
        
    }







    private function fetch($url)
    {
        $apiKey = Yii::$app->params['newsApiKey'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'User-Agent: PortalBeritaYii2/1.0 (+http://localhost:9091)'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

       // return json_decode($response, true);

        $data = json_decode($response, true);

        
        if ($data['status'] === 'ok') {
            return   json_decode($response, true);

        } else {
            echo "Error from API: " . ($data['message'] ?? 'Unknown error');
            return null;
        }
        
    }
}