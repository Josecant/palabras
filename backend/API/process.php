<?php
	
	header('Content-Type: application/json');
	
	require_once("../class/funciones.class.php");
	require_once("../conf/params.conf.php");
	
	//------------------------------------------------------------------------------------
	$obj = new Consulta( $_SERVER['REQUEST_METHOD'], $_REQUEST , $connection);
	if((int)method_exists($obj, $obj->petition ) > 0){
		$result = $obj->get_request_by_method();
		call_user_func_array( array($obj, $obj->petition), $result );		
	}else{
		$obj->report_status(406);
	}
	$data = $obj->get_response();
	//------------------------------------------------------------------------------------

	if(is_array($data)){
		echo json_encode( $data );
	}

?>