<?php


class Database {

    var $db = null;

    public function __construct($dbconfig) {

        $this->db = mysql_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pass']);
        if($this->db == false){
            die('could not connect:'.mysql_error());
        }
        mysql_select_db($dbconfig['db']);

    }

    public function query($sql){
        return mysql_query($sql);
    }

    public function getResults($result){
        if(mysql_num_rows($result) == 0){
            return false;
        } else {
            return mysql_fetch_assoc($result);
        }
    }

    public function __deconstruct(){
        mysql_close($this->db);
    }

}