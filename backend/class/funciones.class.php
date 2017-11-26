<?php
require_once("rest/API.class.php");

class Consulta extends API{

	public function __construct($request_method, $request, $param_conection){
		parent::__construct($request_method, $request, $param_conection);
	}

	public function r_usuario(){

		switch ( $this->get_request_method() ) {

			case 'GET':
				$where = '';					
				if( $this->detail != null ){
					$where.= ' WHERE id='.$this->detail;
				} else {
					$where.= ' ORDER BY puntos ASC';
				}
				
				$sql = "SELECT * FROM usuario".$where;
				if( $result = $this->myConnect->query( $sql ) ){
					$this->response['data'] = $result;
					$this->report_status(200) ;
				}else{
					$this->report_status(0);
				}

			break;

			case 'POST':

				$p =  ( isset($_POST) ) ? (object)$_POST : null;
				$g =  ( isset($_GET) )  ? (object)$_GET  : null;
				
				if( !isset($g->detail) ){
					$sql = "INSERT INTO usuario (usuario, contrasena, puntos) values ( '$p->usuario', '$p->contrasena', '0')";
				
					if( $this->myConnect->query( $sql ) ){
						$this->response['data'] = $this->myConnect->connection->insert_id;
						$this->report_status(200); 
					}else{
						$this->report_status(0);
					}
				}
			break;

			default:
				$this->report_status(0);
			break;
		}		
	}

	public function logueo()
	{
		$p =  ( isset($_POST) ) ? (object)$_POST : null;

		$sql = "SELECT * FROM usuario WHERE usuario='".$p->usuario."' AND contrasena='".$p->contrasena."'";
		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200) ; 
		}else{
			$this->report_status(0);
		}
	}

	public function selec_palabra()
	{
		$where = '';					
		if( $this->detail != null ){
			$where.= ' WHERE id_palabra='.$this->detail;
		}

		$sql = "SELECT * FROM palabra".$where;
		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200);
			$total = count($result)-1;
			$id_ps = rand(1,$total);
		}
		return $id_ps;
	}

	public function selec_palabra2()
	{
		$sql = "SELECT * FROM palabra";
		if( $result = $this->myConnect->query( $sql ) ){
			$this->report_status(200);
			$total = count($result)-1;
			$id_ps = rand(1,$total);
		}
		return $id_ps;
	}

	public function jugar()
	{
		$sql = "SELECT * FROM juego WHERE id_usuario=$this->detail";

		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200); 
		} else {
			$pal = $this->selec_palabra2();
			$sql = "INSERT INTO juego (num_palabra, punt_juego, id_usuario, id_palabra) values ( 1, 0, $this->detail, $pal)";
	
			if( $this->myConnect->query( $sql ) ){
				$this->response['data'] = $this->myConnect->connection->insert_id;
				$this->report_status(200);

				$sql = "SELECT * FROM juego WHERE id_usuario=$this->detail";

				if( $result = $this->myConnect->query( $sql ) ){
					$this->response['data'] = $result;
					$this->report_status(200); 
				}
			}
		}

	}

	public function nex()
	{
		$p =  ( isset($_POST) ) ? (object)$_POST : null;
		$total = 0;

		if ($p->pts) {
			$total = $p->pts;
		}

		$pal = $this->selec_palabra2();

		$sql = "UPDATE juego SET num_palabra=$total, id_palabra=$pal  WHERE id_usuario=$this->detail";
		if( $result = $this->myConnect->query( $sql ) ){
			$this->response['data'] = $result;
			$this->report_status(200) ; 
		}
	}

	public function end()
	{
		$sql1= "SELECT * FROM usuario WHERE id_usuario=$this->detail";
		$sql2= "SELECT * FROM juego WHERE id_usuario=$this->detail";

		$result1 = $this->myConnect->query( $sql1 );
		$result2 = $this->myConnect->query( $sql2 );

		//$this->response['data'] = $result2[0]["num_palabra"];

		if ($result2[0]["num_palabra"] > $result1[0]["puntos"] ) {
			$sql = "UPDATE usuario SET puntos=".$result2[0]["num_palabra"]." WHERE id_usuario=$this->detail";
			if( $result = $this->myConnect->query( $sql ) ){
				$this->response['data'] = $result;
				$this->report_status(200) ; 
			}
		}
	}

}

?>