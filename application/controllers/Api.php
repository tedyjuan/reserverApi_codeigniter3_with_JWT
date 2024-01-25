<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController
{
    private $secret_key = "d@&ihaefd823ghr!#fgewf8g2938g!@#94383gf";
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function users_get()
    {
        $date = new Datetime();
        $payload["nik"]     = "123456789";
        $payload["jabatan"] = 'admin';
        $payload["iat"]     = $date->getTimestamp();
        $payload["exp"]     = $date->getTimestamp() + 180;

        $token        = JWT::encode($payload, $this->secret_key);
        // Users from a data store e.g. database
        $users = [
            [
                'id'    => 0,
                'name'  => 'John',
                'email' => 'john@example.com'
            ],
            [
                'id'    => 1,
                'name'  => 'Jim',
                'email' => 'jim@example.com'
            ],
        ];

        $id = $this->get('id');
        $post_data = [
            "token" => $token,
            "data" => $users,
            "payload" => $payload
        ];
        if ($id === null) {
            // Check if the users data store contains users
            if ($users) {
                // Set the response and exit
                $this->response($post_data, 200);
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        } else {
            if (array_key_exists($id, $users)) {
                $this->response($users[$id], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No such user found'
                ], 404);
            }
        }
    }

     public function passing_get(){
        // $this->cekToken_get();
        validasi_token();
        $data_json = array(
            "success"    => false,
            "message"    => "Token berhasil jebol",
            "error_code" => '001',
            "data"       => null
        );
        $this->response($data_json, 404);
     }
    public function cekToken_get()
    {
        try {
            $token = $this->input->get_request_header('Authorization');
            if (!empty($token)) {
                $token = explode(' ', $token)[1];
            }

            $token_decode = JWT::decode($token, $this->secret_key, array('HS256'));
            $date = new Datetime();
            if(!empty($token_decode)){
               $exp  = $token_decode->exp;
               $iat  = $date->getTimestamp();
               if($iat < $exp){
                //    $data_json = array(
                //        "success"          => true,
                //        "message"          => "Token valid",
                //        "error_code"       => '0',
                //        "exp_token"        => $exp,
                //        "iat_tgl_sekarang" => $iat,
                //    );
                //     $this->response($data_json, 200);
                    $token_decode = JWT::decode($token, $this->secret_key, array('HS256'));
               }else{
                   $data_json = array(
                       "success"    => false,
                       "message"    => "Token expaired",
                       "error_code" => '001',
                       "data"       => null
                   );
                    $this->response($data_json, 404);
               }
            }else{
                $data_json = array(
                    "success"    => false,
                    "message"    => "Token is Empty",
                    "error_code" => '002',
                    "data"       => null
                );
                $this->response($data_json, 404);
            }
           
        } catch (Exception $e) {
            $data_json = array(
                "success"    => false,
                "message"    => "Token not valid",
                "error_code" => '003',
                "data"       => null
            );
            $this->response($data_json, 200);
            $this->output->_display();
            exit();
        }
    }
}
