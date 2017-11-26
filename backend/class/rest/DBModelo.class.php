<?php
/*
* clase         DBModelo
* version       1.1
* autor         Alexander|Toscano kikret@gmail.com
* descripcion   Contiene metodos basicos de conexion a bases de datos
* tipo          Publica
*/


class DBModelo {
    public $db_host;
    public $db_user;
    public $db_pass;
    public $db_name;
    public $db_port;
    public $soket;
    public $sql;
    public $rows = array();
    public $connection;

    #construccion
    public function __construct($connection){
        $this->db_host  = $connection['DB_HOST'];
        $this->db_user  = $connection['DB_USER'];
        $this->db_pass  = $connection['DB_PASS'];
        $this->db_name  = $connection['DB_NAME'];
        $this->query    = null;
        $this->rows     = null;
        $this->connection = null;
    }
    #conectar la base de datos
    public function open_connection() {
        if(!isset($this->db_port)){
            $this->db_port = '';
        }

        $this->connection = new mysqli($this->db_host, $this->db_user,$this->db_pass, $this->db_name);
        mysqli_set_charset($this->connection, "utf8");
    }


    public function query($sql){
        $row = null;
        $this->sql = $sql;
        $result = $this->connection->query( $this->sql );
        if(is_object( $result) ){
            while ($objColumnas = $result->fetch_object())  {
                if (is_object($objColumnas)) {
                    $row[] = get_object_vars($objColumnas);
                }
            }    
        }else{
            $row = $result;
        }        

        return $row;
    }

    public function multi_query($sql){
        $row = null;
        $this->sql = $sql;
        $result = $this->connection->multi_query($this->sql);

        if($result!=null){
            while ($objColumnas = $result->fetch_object())  {
                if (is_object($objColumnas)) {
                    $row[] = get_object_vars($objColumnas);
                }
            }
        }

        return $row;
    }

    #desconectar la base de datos
    public function close_connection() {
        $this->connection->close();
    }
    #destruccion de datos de la clase
    public function __destruct(){
        $this->db_host  = null;
        $this->db_user  = null;
        $this->db_pass  = null;
        $this->db_name  = null;
        $this->sql      = null;
        $this->rows     = null;
        $this->connection = null;
    }

}
?>