<?php
namespace App\Classes\BankPortal;
use App\Classes\Base\BankPortal;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class ZibalPortal implements BankPortal{

    protected $merchant = 'zibal';

    CONST PORTAL_STATUS = 'test';

    public $baseUrl = 'https://gateway.zibal.ir/start/';
    public $baseUri = 'https://gateway.zibal.ir/v1/request';
    public $verify = 'https://gateway.zibal.ir/v1/verify';

    public function Request(Array $data)
    {
        $data['merchant'] = $this->merchant;

        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);
        $response = $client->post('/request', [
            'json' => $data,
        ]);
        $responseBody =  json_decode($response->getBody()->getContents(), true);

        if($responseBody["result"] == 100)
        {
            $trackId = $responseBody["trackId"];
            $paymentPageUrl = $this->baseUrl . $trackId;
            return [
                'trackId' => $trackId,
                'paymentPageUrl' => $paymentPageUrl,
            ];
        }else
            return false;
        }

    public function Verify(Array $data)
    {

    }
}


