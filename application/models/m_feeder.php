<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   /*
    * @author : Yusuf Ayuba 
    * 
    */
   
   class M_feeder extends CI_Model {
       
       private $ws_client;
       private $ws_proxy;
       
       function __construct() {
           parent::__construct();
           $this->ws_client = new nusoap_client($this->session->userdata('ws'), true);
           $this->ws_proxy = $this->ws_client->getProxy();
       }
       
       function new_token($username,$password) {
           return $this->ws_proxy->GetToken($username, $password);
       }
       
       function listtable($token) {
           return $this->ws_proxy->ListTable($token);
       }
       
       function getdic($token, $table) {
           return $this->ws_proxy->GetDictionary($token, $table);
       }
       
       function getrset($token, $table, $filter, $order, $limit, $offset) {
           return $this->ws_proxy->GetRecordset($token, $table, $filter, $order, $limit, $offset);
       }
       
       function getrecord($token, $table, $filter) {
           return $this->ws_proxy->GetRecord($token, $table, $filter);
       }
       
       function insertrset($token, $table, $records) {
           return $this->ws_proxy->InsertRecordset($token, $table, json_encode($records));
       }
       
       function insertrecord($token, $table, $records) {
           return $this->ws_proxy->InsertRecord($token, $table, json_encode($records));
       }
       
       function update($token,$table,$records) {
           return $this->ws_proxy->UpdateRecord($token,$table,json_encode($records));
       }
       
       function updaterset($token,$table,$records) {
           return $this->ws_proxy->UpdateRecordset($token, $table, json_encode($records));
       }
       
       function count_all($token,$table) {
           return $this->ws_proxy->GetCountRecordSet($token, $table);
       }
   }
   
/* End of file m_feeder.php */
/* Location: ./application/models/m_feeder.php */