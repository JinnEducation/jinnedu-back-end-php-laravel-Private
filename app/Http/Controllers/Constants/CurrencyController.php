<?php

namespace App\Http\Controllers\Constants;

use App\Models\Currency;
use App\Models\CurrencyLatest;

class CurrencyController extends ConstantController
{

    public function __construct()
    {
        $auditInfo = 'Currency';
        $appPrefix = 'App\\Models';
        $this->modelName = $appPrefix . '\\' . $auditInfo;
        $this->modelTitle = 'currencies';
        $this->curl_options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
            CURLOPT_HTTPHEADER => [
                'accept: application/json'
            ],
            //CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            //CURLOPT_PROXY => "0.0.0.0",
            //CURLOPT_PROXYPORT => "80",
        );
        $this->apiKey = '4146caa721-7b424cd3a8-sh6gtc';
    }

    // public function latestExchange($id, $httpResponse = true)
    // {
    //     $currency = \App\Models\Currency::find($id);
    //     if(!$currency) {
    //         $response = [
    //                 'success' => false,
    //                 'message' => 'currency-dose-not-exist',
    //                 'msg-code' => '111'
    //         ];
    //         if($httpResponse) {
    //             return response($response, 400);
    //         }
    //         return $response;
    //     }

    //     $currencyLatest = \App\Models\CurrencyLatest::query()->where('currency_id', $currency->id)->whereRaw('TIMESTAMPDIFF(HOUR, `created_at`, Now())<=1')->orderBy('id', 'desc')->first();
    //     if($currencyLatest) {
    //         $response = [
    //             'success' => true,
    //             'message' => 'exchange-showen-successfully',
    //             'result' => $currencyLatest
    //         ];
    //         if($httpResponse) {
    //             return response($response, 200);
    //         }
    //         return $response;
    //     } else {

    //         $latest = $this->getLatest();

    //         if($latest && count($latest['data']) > 0) {
    //             foreach($latest['data'] as $key => $val) {

    //                 // return $key;
    //                 $currencyId = \App\Models\Currency::whereRaw('UPPER(name)=?', [$key])->first();
    //                 if($currencyId) {
    //                     $currencyLatestEx = new \App\Models\CurrencyLatest();
    //                     $currencyLatestEx->currency_id = $currencyId->id;
    //                     $currencyLatestEx->currency_code = $key;
    //                     $currencyLatestEx->exchange = $val;
    //                     // $currencyLatestEx->last_updated_at = $latest['meta']['last_updated_at'];
    //                     $currencyLatestEx->save();
    //                 }
    //             }

    //         }

    //         $currencyLatest = \App\Models\CurrencyLatest::query()->where('currency_id', $currency->id)->whereRaw('TIMESTAMPDIFF(HOUR, `created_at`, Now())<=1')->orderBy('id', 'desc')->first();
    //         if($currencyLatest) {
    //             $response = [
    //                 'success' => true,
    //                 'message' => 'exchange-showen-successfully',
    //                 'result' => $currencyLatest
    //             ];
    //             if($httpResponse) {
    //                 return response($response, 200);
    //             }
    //             return $response;
    //         } else {
    //             $response = [
    //                        'success' => false,
    //                        'message' => 'unknown-error',
    //                        'msg-code' => '111'
    //             ];
    //             if($httpResponse) {
    //                 return response($response, 400);
    //             }
    //             return $response;
    //         }
    //     }

    //     $response = [
    //                 'success' => false,
    //                 'message' => 'unknown-error',
    //                 'msg-code' => '222'
    //     ];

    //     if($httpResponse) {
    //         return response($response, 200);
    //     }
    //     return $response;
    // }

    public function latestExchange($id, $httpResponse = true)
    {
        $currency = \App\Models\Currency::find($id);

        if (!$currency) {
            $response = [
                'success' => false,
                'message' => 'currency-does-not-exist',
                'msg-code' => '111'
            ];

            return $httpResponse ? response($response, 200) : $response;
        }

        $currencyLatest = \App\Models\CurrencyLatest::query()
            ->where('currency_id', $currency->id)
            ->whereRaw('TIMESTAMPDIFF(HOUR, `created_at`, ?) <= 1', [NOW()])
            ->orderBy('id', 'desc')
            ->first();
        
        if ($currencyLatest) {
            $response = [
                'success' => true,
                'message' => 'exchange-shown-successfully',
                'result' => $currencyLatest
            ];

            return $httpResponse ? response($response, 200) : $response;
        }

        $latest = $this->getLatest();

        if ($latest && isset($latest['results']) && count($latest['results']) > 0) {
            foreach ($latest['results'] as $key => $val) {
                // Use strtoupper for case-insensitive comparison
                $currencyId = \App\Models\Currency::whereRaw('UPPER(name) = ?', [strtoupper($key)])->first();

                if ($currencyId) {
                    $currencyLatestEx = new \App\Models\CurrencyLatest();
                    $currencyLatestEx->currency_id = $currencyId->id;
                    $currencyLatestEx->currency_code = $key;
                    $currencyLatestEx->exchange = $val;
                    $currencyLatestEx->save();
                }
            }
        }

        $currencyLatest = \App\Models\CurrencyLatest::query()
            ->where('currency_id', $currency->id)
            ->whereRaw('TIMESTAMPDIFF(HOUR, `created_at`, ?) <= 1', [NOW()])
            ->orderBy('id', 'desc')
            ->first();

        if ($currencyLatest) {
            $response = [
                'success' => true,
                'message' => 'exchange-shown-successfully',
                'result' => $currencyLatest
            ];

            return $httpResponse ? response($response, 200) : $response;
        } else {
            $response = [
                'success' => false,
                'message' => 'unknown-error',
                'msg-code' => '111'
            ];

            return $httpResponse ? response($response, 200) : $response;
        }

        $response = [
            'success' => false,
            'message' => 'unknown-error',
            'msg-code' => '222'
        ];

        return $httpResponse ? response($response, 200) : $response;
    }



    public function getCurrencies()
    {
        $ch = curl_init("https://api.fastforex.io/currencies". '?api_key=' . $this->apiKey);
        // https://api.freecurrencyapi.com/v1/latest?apikey=FwUn9kysYr7NbV3UdtK4f07NyahdNIo9aJyVugao&currencies=EUR%2CUSD%2CCAD
        curl_setopt_array($ch, $this->curl_options);
        $output = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);
        //dd( $output );
        return json_decode($output, true);
    }

    public function getLatest()
    {
        $currenciesList = '';
        $currencies = \App\Models\Currency::all();
        foreach($currencies as $currency) {
            $currenciesList .= strtoupper($currency->name) . ',';
        }
        $ch = curl_init("https://api.fastforex.io/fetch-multi?from=USD&to=" . trim($currenciesList, ',') . '&api_key=' . $this->apiKey);
        curl_setopt_array($ch, $this->curl_options);
        $output = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);
        //dd( $output );
        return json_decode($output, true);
    }





    // public function latestExchange($id, $httpResponse = true)
    // {
    //     $currency = $this->currencyModel->find($id);

    //     if (!$currency) {
    //         $response = [
    //             'success' => false,
    //             'message' => 'currency-does-not-exist',
    //             'msg-code' => '111'
    //         ];

    //         return $httpResponse ? response($response, 400) : $response;
    //     }

    //     $currencyLatest = $this->currencyLatestModel
    //         ->where('currency_id', $currency->id)
    //         ->whereRaw('TIMESTAMPDIFF(HOUR, `created_at`, NOW()) <= 1')
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     if ($currencyLatest) {
    //         $response = [
    //             'success' => true,
    //             'message' => 'exchange-shown-successfully',
    //             'result' => $currencyLatest
    //         ];

    //         return $httpResponse ? response($response, 200) : $response;
    //     } else {
    //         $latest = $this->getLatest();

    //         if ($latest && count($latest['data']) > 0) {
    //             foreach ($latest['data'] as $key => $val) {
    //                 $currencyId = $this->currencyModel
    //                     ->whereRaw('UPPER(name)=?', [$val['code']])
    //                     ->first();

    //                 if ($currencyId) {
    //                     $currencyLatestEx = new CurrencyLatest();
    //                     $currencyLatestEx->currency_id = $currencyId->id;
    //                     $currencyLatestEx->currency_code = $val['code'];
    //                     $currencyLatestEx->exchange = $val['value'];
    //                     $currencyLatestEx->last_updated_at = $latest['meta']['last_updated_at'];
    //                     $currencyLatestEx->save();
    //                 }
    //             }
    //         }

    //         $currencyLatest = $this->currencyLatestModel
    //             ->where('currency_id', $currency->id)
    //             ->whereRaw('TIMESTAMPDIFF(HOUR, `created_at`, NOW()) <= 1')
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         if ($currencyLatest) {
    //             $response = [
    //                 'success' => true,
    //                 'message' => 'exchange-shown-successfully',
    //                 'result' => $currencyLatest
    //             ];

    //             return $httpResponse ? response($response, 200) : $response;
    //         } else {
    //             $response = [
    //                 'success' => false,
    //                 'message' => 'unknown-error',
    //                 'msg-code' => '111'
    //             ];

    //             return $httpResponse ? response($response, 400) : $response;
    //         }
    //     }

    //     $response = [
    //         'success' => false,
    //         'message' => 'unknown-error',
    //         'msg-code' => '222'
    //     ];

    //     return $httpResponse ? response($response, 200) : $response;
    // }

    // public function getCurrencies()
    // {
    //     $apiKey = config('services.freecurrencyapi.api_key');
    //     $url = "https://api.freecurrencyapi.com/v1/latest?apikey={$apiKey}&currencies=EUR%2CUSD%2CCAD";

    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, $this->getCurlOptions());
    //     $output = curl_exec($ch);

    //     // Handle errors
    //     $this->handleCurlError($ch);

    //     curl_close($ch);

    //     return json_decode($output, true);
    // }

    // public function getLatest()
    // {
    //     $currenciesList = '';
    //     $currencies = $this->currencyModel->all();

    //     foreach ($currencies as $currency) {
    //         $currenciesList .= strtoupper($currency->name) . ',';
    //     }

    //     $apiKey = config('services.freecurrencyapi.api_key');
    //     $url = "https://api.freecurrencyapi.com/v1/latest?apikey={$apiKey}&currencies=" . trim($currenciesList, ',');

    //     $ch = curl_init($url);
    //     curl_setopt_array($ch, $this->getCurlOptions());
    //     $output = curl_exec($ch);

    //     // Handle errors
    //     $this->handleCurlError($ch);

    //     curl_close($ch);

    //     return json_decode($output, true);
    // }

    // protected function getCurlOptions()
    // {
    //     return [
    //         // Your cURL options here
    //     ];
    // }

    // protected function handleCurlError($ch)
    // {
    //     $err = curl_errno($ch);

    //     if ($err) {
    //         $response = [
    //             'success' => false,
    //             'message' => 'cURL error: ' . curl_error($ch),
    //             'msg-code' => '333'
    //         ];

    //         response($response, 500)->send();
    //         exit;
    //     }
    // }


    public function currencyTest($from, $to)
    {

        $url = "https://api.freecurrencyapi.com/v1/latest?apikey=FwUn9kysYr7NbV3UdtK4f07NyahdNIo9aJyVugao&currencies={$from}%2C{$to}";

    }
}
