<?php
// +----------------------------------------------------------------------
// | serverAlert mysql操作类
// +----------------------------------------------------------------------
class mysql{
    //数据库连接返回值
    private $conn;
    private $host;
    private $port;
    private $user;
    private $pass;
    private $db;
    private $charset;
    /**
     * [构造函数，返回值给$conn]
     * @param [string] $hostname [主机名]
     * @param [string] $username[用户名]
     * @param [string] $password[密码]
     * @param [string] $dbname[数据库名]
     * @param [string] $charset[字符集]
     * @return [null]
     */
    public function __construct($config = array()){
        $this->host = $config['host'] ? $config['host'] : 'localhost';
        $this->port = $config['port'] ? $config['port'] : '3306';
        $this->user = $config['user'] ? $config['user'] : 'root';
        $this->pass = $config['pass'] ? $config['pass'] : 'root';
        $this->db   = $config['db']   ? $config['db']   : 'serverAlert';
        $this->charset=isset($config['charset']) ? $config['charset'] : 'utf8';
        $this->connect();
    }

    //连接数据库
    public function connect(){
        $conn = @mysql_connect($this->host,$this->user,$this->pass);
        if(!$conn){
            echo '连接失败，请联系管理员';
            exit;
        }
        $this->conn = $conn;
        $res = mysql_select_db($this->db);
        if(!$res){
            echo '连接失败，请联系管理员';
            exit;
        }
        mysql_set_charset($this->charset);
    }

    public function __destruct(){
        mysql_close();
    }
    /**
     * [getAll 获取所有信息]
     * @param [string] $sql [sql语句]
     * @return [array] [返回二维数组]
     */
    public function getAll($sql){
        $result = mysql_query($sql,$this->conn);
        $data = array();
        if($result && mysql_num_rows($result)>0){
            while($row = mysql_fetch_assoc($result)){
                $data[] = $row;
            }
        }
       return $data;
    }
    /**
     * [getOne 获取单条数据]
     * @param [string] $sql [sql语句]
     * @return [array] [返回一维数组]
     */
    public function getOne($sql){
        $result = mysql_query($sql,$this->conn);
        $data = array();
        if($result && mysql_num_rows($result)>0){
            $data = mysql_fetch_assoc($result);
        }
        return $data;
    }

    /**
     * [getOne 获取单条数据]
     * @param [string] $table [表名]
     * @param [string] $data [由字段名当键，属性当键值的一维数组]
     * @return [type] [返回false或者插入数据的id]
     */

    public function insert($table,$data){
        $str = '';
        $str .="INSERT INTO `$table` ";
        $str .="(`".implode("`,`",array_keys($data))."`) ";
        $str .=" VALUES ";
        $str .= "('".implode("','",$data)."')";
        $res = mysql_query($str,$this->conn);
        if($res && mysql_affected_rows()>0){
            return mysql_insert_id();
        }else{
            return false;
        }
    }
    /**
     * [update 更新数据库]
     * @param [string] $table [表名]
     * @param [array] $data [更新的数据，由字段名当键，属性当键值的一维数组]
     * @param [string] $where [条件，‘字段名'=‘字段属性']
     * @return [type] [更新成功返回影响的行数，更新失败返回false]
     */
    public function update($table,$data,$where){
        $sql = 'UPDATE '.$table.' SET ';
        foreach($data as $key => $value){
            $sql .= "`{$key}`='{$value}',";
        }
        $sql = rtrim($sql,',');
        $sql .= " WHERE $where";
        $res = mysql_query($sql,$this->conn);
        if($res && mysql_affected_rows()){
            return mysql_affected_rows();
        }else{
            return false;
        }
    }

    /**
     * [delete 删除数据]
     * @param [string] $table [表名]
     * @param [string] $where [条件，‘字段名'=‘字段属性']
     * @return [type] [成功返回影响的行数，失败返回false]
     */
    public function del($table,$where){
        $sql = "DELETE FROM `{$table}` WHERE {$where}";
        $res = mysql_query($sql,$this->conn);
        if($res && mysql_affected_rows()){
            return mysql_affected_rows();
        }else{
            return false;
        }
    }
}