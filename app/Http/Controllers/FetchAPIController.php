<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Road;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchAPIController extends Controller
{
    public function Test()
    {
        echo "dasjkdhsjkahdas";
    }

    public function FetchDistricts($data)
    {
        $response = $data['response'];

        $district_db = new District();
        $district_db->name = $data['name'];
        if (array_key_exists(0, $response->rows))
        {
            $district_db->api_id = $response->rows[0]->cnt_districts[0]->id;
            $district_db->type = $response->rows[0]->cnt_districts[0]->type;
            $district_db->x = $response->rows[0]->cnt_districts[0]->x;
            $district_db->y = $response->rows[0]->cnt_districts[0]->y;
        }
        $district_db->save();
    }

    public function FetchSettlements($data)
    {
        $district_id = District::where('name', $data['name'])->value('id');
        $response = $data['response'];

        if (!empty($response->rows))
        {
            foreach ($response->rows[1]->settlements as $settlement)
            {
                $settlement_db = new Settlement();
                $settlement_db->name = $settlement->name;
                $settlement_db->district_id = $district_id;
                $settlement_db->api_id = $settlement->id;
                $settlement_db->type = $settlement->type;
                $settlement_db->x = $settlement->x;
                $settlement_db->y = $settlement->y;
                $settlement_db->save();
            }
        }
    }

    public function FetchRoads($data)
    {
        $payload = '{
                    "tpIdRegion": "' . $data . '",
                    "road": "",
                    "guid": "cc0ea781e97bc14971afc2e832344b7c",
                    "lng": "az"
                    }';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://extra_api.gomap.az:444/Main.asmx/getRoadsNew');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//                                                'Authorization: Basic ' . config('local.client.credentials_encoded')]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($curl);

        //Don't Delete following comments. It's for determining curl error.
        if (!curl_exec($curl))
        {
            die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }

        //        var_dump($httpcode); die();
        curl_close($curl);

        $response = str_replace('{"d":null}', "", $response);

        $response = json_decode($response);
//        var_dump($response->names->roads);die();

        $type = explode('_', $data)[0];
        $api_id = explode('_', $data)[1];
        $district_id = District::where('api_id', $api_id)->value('id');

        foreach ($response->names->roads as $road)
        {
            $road_db = new Road();
            $road_db->api_id = $road->id;
            $road_db->district_id = $district_id;
            $road_db->name = $road->name;
            $road_db->type = $type;
            $road_db->x = $road->x;
            $road_db->y = $road->y;
            $road_db->save();
        }
    }

    public function Fetch()
    {
        $districts = ["naxçıvan", "ŞƏRUR", "SƏDƏRƏK", "ŞAHBUZ", "CULFA", "ORDUBAD", "BABƏK", "KƏNGƏRLİ"];

        foreach ($districts as $district)
        {
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

            $response = curl_exec($curl);

            //Don't Delete following comments. It's for determining curl error.
            if (!curl_exec($curl))
            {
                die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            }

            //        var_dump($httpcode); die();
            curl_close($curl);

            $response = str_replace('{"d":null}', "", $response);
//            var_dump($response);die();

            //parse jwt start
            $response = json_decode($response);

            $this->FetchDistricts(['response' => $response, 'name' => $district]);
            $this->FetchSettlements(['response' => $response, 'name' => $district]);
            if (array_key_exists(0, $response->rows))
            {
                $_id = $response->rows[0]->cnt_districts[0]->id;
                $type = $response->rows[0]->cnt_districts[0]->type;
                $this->FetchRoads($type . '_' . $_id);
            }
            continue;

            var_dump($response->rows[1]->settlements) . "<br>";
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
