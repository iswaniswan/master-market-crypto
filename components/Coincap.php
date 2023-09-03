<?php 

namespace app\components;

use linslin\yii2\curl\Curl;
use Yii;
use yii\base\Exception;

class Coincap {

    private $secrets;
    protected $apiKey;

    public string $urlAsset = 'api.coincap.io/v2/assets';

    protected function createRequest($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->apiKey
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function __construct(){
        $secrets = Yii::$app->params['secrets']['coincap'];
        if ($secrets == null) {
            throw new Exception('secret key error');
        }
        $this->secrets = $secrets;
        $this->apiKey = $secrets['apiKey'];
    }

    public function getAssets($refresh=false)
    {
        $response = $this->createRequest($this->urlAsset);
        return $response;
    }

    public function getAssetsHistory($id, $interval='d1')
    {
        $url = $this->urlAsset . "/$id/history?interval=$interval";
        $response = $this->createRequest($url);
        return $response;
    }

}


?>