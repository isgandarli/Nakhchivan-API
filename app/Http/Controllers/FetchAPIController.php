<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchAPIController extends Controller
{
    public function Test()
    {
        echo "dasjkdhsjkahdas";
    }

    public function FetchDistricts()
    {
        $districts = ["NAXÇIVAN", "ŞƏRUR", "SƏDƏRƏK", "ŞAHBUZ", "CULFA", "ORDUBAD", "BABƏK", "KƏNGƏRLİ"];

        $districts = ["ŞƏRUR", "SƏDƏRƏK", "ŞAHBUZ", "CULFA", "ORDUBAD", "BABƏK", "KƏNGƏRLİ"];

        foreach ($districts as $district)
        {
//1C part START
            $payload = '{
                        "region" : "' . $district . '",
                        "guid": "cc0ea781e97bc14971afc2e832344b7c",
                        "lng": "az"
                    }';

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, 'https://api.gomap.az:444/Main.asmx/getRegionsNew');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//                                                'Authorization: Basic ' . config('local.client.credentials_encoded')]);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

            //Proxy details
//        $proxy = config('local.proxy_local.url');
//        $proxyUsername = config('local.proxy_local.username');
//        $proxyPassword = config('local.proxy_local.password');
//
//        curl_setopt($curl, CURLOPT_PROXY, $proxy);
//        curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxyUsername . ':' . $proxyPassword);
//        curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);

            $response = curl_exec($curl);

            //Don't Delete following comments. It's for determining curl error.
            if (!curl_exec($curl))
            {
                die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            }
//        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//        var_dump($httpcode); die();
            curl_close($curl);


            $response = str_replace('{"d":null}', "", $response);
//            var_dump($response);die();

            //parse jwt start
            $response = json_decode($response);
            var_dump($response->rows[0]) . "<br>";
            continue;

            $district_db = new District();
            $district_db->name = $district;
            if(array_key_exists(0,$response->rows))
            {
//                $district_db->type=
            }

        }
    }

    public function FetchSettlements()
    {

    }

    public function FetchRoads()
    {

    }

    public function Fetch()
    {
        $districts = ["ŞƏRUR", "SƏDƏRƏK", "ŞAHBUZ", "CULFA", "ORDUBAD", "BABƏK", "KƏNGƏRLİ"];

        foreach ($districts as $district)
        {
//1C part START
            $payload = '{
                        "region" : "' . $district . '",
                        "guid": "cc0ea781e97bc14971afc2e832344b7c",
                        "lng": "az"
                    }';

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, 'https://api.gomap.az:444/Main.asmx/getRegionsNew');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//                                                'Authorization: Basic ' . config('local.client.credentials_encoded')]);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

            //Proxy details
//        $proxy = config('local.proxy_local.url');
//        $proxyUsername = config('local.proxy_local.username');
//        $proxyPassword = config('local.proxy_local.password');
//
//        curl_setopt($curl, CURLOPT_PROXY, $proxy);
//        curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxyUsername . ':' . $proxyPassword);
//        curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);

            $response = curl_exec($curl);

            //Don't Delete following comments. It's for determining curl error.
            if (!curl_exec($curl))
            {
                die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            }
//        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//        var_dump($httpcode); die();
            curl_close($curl);


            $response = str_replace('{"d":null}', "", $response);
//            var_dump($response);die();

            //parse jwt start
            $response = json_decode($response);
            var_dump($response->rows[0]->cnt_districts[0]->x) . "<br>";
            continue;
            //1C part END

//            if(array_key_exists(0,$response->rows))

//            try
//            {
//                DB::beginTransaction();
//                $success = District::insert
//                DB::commit();
//            }
//            catch (\Exception $exception)
//            {
//                DB::rollBack();
//                Log::error('Transaction failed: ' . $exception->getMessage());
//                throw $exception;
////            die($exception->getMessage());
//            }
        }
    }
}
