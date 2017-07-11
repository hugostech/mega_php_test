<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 12:24 PM
 */

namespace hugo;

require_once 'config/database.php';

class Model
{
    public $db;

    protected $db_table;

    protected $schema;

    protected $table_id;

    public function __construct()
    {
        global $config;
        // Create connection
        $this->db = new \mysqli($config['db_host'],$config['db_username'],$config['db_password'],$config['db_database_name']);


        // Check connection
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        $this->table_id = 'id';
        //crate table

        $this->init();
    }

    /*
     * check $db_table exist or not*/
    private function init(){
        $this->db->query($this->schema);

    }

    /**
     * insert data in database
     * @param: array $data
     * @return sucess return db id or -1*/
    public function insert($data){
        $query = 'insert into '.$this->db_table;
        $columns = array();
        $values = array();
        foreach ($data as $key=>$value){
            $columns[] = $key;
            $values[] = '"'.$value.'"';
        }

        $query .= '('.implode(',',$columns).')';
        $query .= ' values ';
        $query .= '('.implode(',',$values).')';

        if ($this->db->query($query)===true){
            return $this->db->insert_id;
        }else{
            return -1;
        }

    }

    /*
     * output all item in this table*/
    public function all(){
        $query = 'select * from '.$this->db_table;
        $result = $this->db->query($query);
        $rows = $result->fetch_all(MYSQL_ASSOC);
        return $rows;
    }

    /*
     * find row by id*/
    public function find($id){
        $query = 'select * from '.$this->db_table;
        $query .= ' where '.$this->table_id.'='.$id;
        $result = $this->db->query($query);
        $row = $result->fetch_row();
        return $row;

    }

    /*update row by id*/
    public function update($data,$id){
        $query = 'update '.$this->db_table;
        $query .= ' set ';
        foreach ($data as $key=>$value){
            $query .= "$key=\"$value\",";
        }
        $query = rtrim($query,',');
        $query .= ' where '.$this->table_id.'='.$id;
        $this->db->query($query);
    }
}