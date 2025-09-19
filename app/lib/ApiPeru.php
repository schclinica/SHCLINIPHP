<?php 
namespace lib;

class ApiPeru{

    /** CONSULTAR MIS DATOS POR DNI  */
    public static function SearchDataPersonDni(String $dni){
        $params = json_encode(['dni' => $dni]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apiperu.dev/api/dni",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer '.env("TOKEN_API_PERU")
            ],
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo  $response;
        }
    }
}