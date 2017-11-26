<?php

/*
* clase         API
* version       1.1
* autor         Alexander|Toscano kikret@gmail.com
* descripcion   Contiene metodos REST
* tipo          Publica
*/

require_once("DBModelo.class.php");


class API{

	public $petition = null;
	public $detail = null;
	protected $response = array("status"=>"", "data"=>"", "message"=>"");
	protected $request_method = null;
	protected $status;	
	public $myConnect = null;

	public function __construct($request_method, $request, $param_conection){
		
		$this->request_method = $request_method;
		$this->petition = $request['petition'];
		$this->detail = ( isset($request['detail'])? $request['detail']: null );

		$this->myConnect = new DBMOdelo($param_conection);
		$this->myConnect->open_connection();

	}

	public function get_response(){
		return $this->response;
	}

	public function get_request_method(){
		return $this->request_method;
	}

	public function get_request_by_method(){

		switch ( $this->request_method ) {
			case 'POST':
					$arr_request = $_POST; 
				break;
			case 'GET':
					$arr_request = $_GET;
				break;
			case 'DELETE':
					$arr_request = $_REQUEST;
				break;
			case 'PUT':
					$arr_request = $_REQUEST;
				break;
			default:
					//handle_error($request); 
				break;
		}

		return $arr_request;
	}

	public function report_status($status){
		switch ($status) {
			case 200:
					$this->response['message'] = 'HTTP/1.1 200 OK';
					$this->response['status'] = 'success';
				break;
			case 201:
					$this->response['message'] = 'HTTP/1.1 201 Created';
					$this->response['status'] = 'success';
				break;
			case 204:
					$this->response['message'] = 'HTTP/1.1 204 No Content';
					$this->response['data'] = null;
					$this->response['status'] = 'fail';
				break;
			case 404:
					$this->response['message'] = 'HTTP/1.1 404 Not Found';
					$this->response['data'] = null;
					$this->response['status'] = 'error';
				break;
			case 406:
					$this->response['message'] = 'HTTP/1.1 406 Not Acceptable';
					$this->response['data'] = null;
					$this->response['status'] = 'fail';
				break;
			//messages de consultas
			case 0:
					$this->response['message'] = '0 rows affected';
					$this->response['data'] = null;
					$this->response['status'] = 'success';
				break;
			case 1:
					$this->response['message'] = 'Row eliminate';
					$this->response['data'] = null;
					$this->response['status'] = 'success';
				break;
			case 3:
					$this->response['message'] = 'Row affected';
					$this->response['data'] = null;
					$this->response['status'] = 'success';
				break;
			case 4:
					$this->response['message'] = '0 Row affected';
					$this->response['data'] = null;
					$this->response['status'] = 'error';
				break;
			
			default:
					$this->response['message'] = 'HTTP/1.1 500 Internal Server Error';
					$this->response['data'] = null;
					$this->response['status'] = 'error';
				break;	
		}
	}

}


?>