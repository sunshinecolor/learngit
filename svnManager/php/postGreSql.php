<?php
/**
 * Created by PhpStorm.
 * User: pengyingpeng
 * Date: 2016/7/22 0022
 * Time: 10:24
 */

//login 检查
if(!(isset($_SESSION['user_login']) && ($_SESSION['user_login']==1))){
    alert('null','login.php');
    exit;
}
$username = isset($_SESSION['user_name'])?$_SESSION['user_name']:'';


//增删查改类
class postGreSql{
    protected $where;
    protected $order;
    protected $limit;
    protected $join;
    protected $table;
    protected $field;
    protected $group;
    protected $key;
    protected $value;
    protected $updateInfo;
    protected $mode;
    protected $sql;
    protected $andwhere;

    public function __construct($db){
        $this->pg = $db;
    }

    //sqlCheck 防sql注入
    public function sqlCheck($sql){
        $bool = preg_match("#select|insert|update|delete|union|into|load_file|outfile#i",$sql);
        if($bool) die("Cannot enter special characters");
        return $sql;
    }

    //clearInfo 清空信息
    public function clearInfo(){
        $this->where = '';
        $this->field = '';
        $this->limit = '';
        $this->order = '';
        $this->group = '';
        $this->table = '';
        $this->join  = '';
        $this->value = '';
        $this->key = '';
        $this->updateInfo = '';
        return $this;
    }

    //table 表名
    public function table($table=null){
        $sql = $this->sqlCheck($table);
        $this->table = $sql;
        return $this;
    }

    //where 条件
    public function where($where=null){
        if(is_array($where)){
            $str = array_key_exists('_logic',$where)?$where['_logic']:'and';
            unset($where['_logic']);
            if(count($where) <= 1) $str='';
            $sql = '';
            foreach($where as $k=>$v){
                if(is_array($v)){
                    if($v[0] == 'between'){
                        $sql.=$k." between '".$v[1][0]."' and '".$v[1][1]."' ".$str.' ';
                        echo $sql;
                    }else{
                        $sql.=$k." ".$v[0]." '".$v[1]."' ".$str.' ';
                    }
                }else{
                    if(!is_numeric($v)){
                        $v = "'".$v."'";
                    }
                    $sql.=$k.' = '.$v.' '.$str.' ';
                }
            }
            $sql = substr($sql,0,strrpos($sql,$str)-1);
        }else{
            $sql = $where;
        }
        $sql = $this->sqlCheck($sql);
        $this->where = $sql;
        return $this;
    }

    //order 排序
    public function order($order=null){
        $sql = $this->sqlCheck($order);
        $this->order = $sql;
        return $this;
    }

    //limit 查询区间
    public function limit($star=null,$show=null){
        if(empty($star) && empty($show)) return $this;
        if((int)$star < 0 || (int)$show < 0) die("Star and show must be integer");
        $this->limit = ' limit '.$show.' offset '.$star;
        return $this;
    }

    //join 关联查询
    public function join($join=null,$mode='inner '){
        $sql = $this->sqlCheck($join);
        if(!empty($join)){
            $this->join .= ' '.$mode.' '.'join '.$sql;
            $this->mode = $mode;
        }
        return $this;
    }

    //field 查询字段
    public function field($field=null){
        $sql = $this->sqlCheck($field);
        $this->field = $sql;
        return $this;
    }

    //group 分组查询
    public function group($group=null){
        $sql = $this->sqlCheck($group);
        $this->group = $sql;
        return $this;
    }

    /**
     * 获取插入、更新的数据
     * $status 1，表示添加操作。2，表示更新操作
     * @param array $info
     * @param int $status
     * @return $this
     *
     */
    public function data($info=array(),$status=1){
        if($status == 1){
            $fields = '';//字段
            $value  = '';//值
            if(!empty($info)){
                foreach($info as $k=>$v){
                    $va = $this->sqlCheck($v);
                    $fields.=$k.',';
                    $value.="'".$va."'".',';
                }
            }
            $fields = substr($fields,0,strlen($fields)-1);
            $value = substr($value,0,strlen($value)-1);
            $this->key = $fields;
            $this->value = $value;
        }else{
            $updateInfo = '';//更新信息   字段=值，字段=值...
            if(!empty($info)){
                foreach($info as $k=>$v){
                    $va = $this->sqlCheck($v);
                    $updateInfo.=$k."='".$va."',";
                }
            }
            $updateInfo = substr($updateInfo,0,strlen($updateInfo)-1);
            $this->updateInfo = $updateInfo;
        }
        return $this;
    }

    //count 总记录数
    public function count(){
        $sql = "select count(*) from ";
        $sql.=!empty($this->table)?$this->table:die("Table can't be null");
        $sql.=!empty($this->join)?' '.$this->mode.' join '.$this->join:'';
        $sql.=!empty($this->where)?' where '.$this->where:'';
        $this->sql = $sql;
        $result = @pg_query($this->pg,$sql);
        $res = @pg_fetch_all($result);
        $this->clearInfo();
        return $res[0]['count'];
    }

    //select 查找全部
    public function select(){
        $sql = 'select ';
        $sql.=!empty($this->field)?$this->field:'*';
        $sql.=!empty($this->table)?' from '.$this->table:die("Table can't be null");
        $sql.=!empty($this->join)?$this->join:'';
        $sql.=!empty($this->where)?' where '.$this->where:'';
        $sql.=!empty($this->group)?' group by '.$this->group:'';
        $sql.=!empty($this->order)?' order by '.$this->order:'';
        $sql.=!empty($this->limit)?' '.$this->limit:'';
        $this->sql=$sql;
        $result = @pg_query($this->pg,$sql);
        $res = @pg_fetch_all($result);
        $this->clearInfo();
        empty($res)?$res = array():'';
        return $res;
    }

    //select 查找全部
    public function andselect(){
        $sql = 'select ';
        $sql.=!empty($this->field)?$this->field:'*';
        $sql.=!empty($this->table)?' from '.$this->table:die("Table can't be null");
        $sql.=!empty($this->join)?$this->join:'';
        $sql.=!empty($this->where)?' and '.$this->where:'';
        $sql.=!empty($this->group)?' group by '.$this->group:'';
        $sql.=!empty($this->order)?' order by '.$this->order:'';
        $sql.=!empty($this->limit)?' '.$this->limit:'';
        $this->sql=$sql;
        $result = @pg_query($this->pg,$sql);
        $res = @pg_fetch_all($result);
        empty($res)?$res = array():'';
        $this->clearInfo();
        return $res;
    }

    //add 添加
    public function add($data=array()){
        if($data) $this->data($data);
        $sql ='insert into ';
        $sql.=!empty($this->table)?$this->table.'(':die("Table can't be null");
        $sql.=$this->key.')';
        $sql.=' values (';
        $sql.=$this->value.')';
        $this->sql=$sql;
        $bool = @pg_query($this->pg,$sql);
        $this->clearInfo();
        return $bool;
    }

    //save 更新
    public function save($data=array()){
        if($data) $this->data($data,2);
        $sql ='update ';
        $sql.=!empty($this->table)?$this->table.' set ':die("Table can't be null");
        $sql.=$this->updateInfo.' where ';
        $sql.=!empty($this->where)?$this->where:die("Condition cannot be empty");
        $this->sql=$sql;
        $bool = @pg_query($this->pg,$sql);
        $this->clearInfo();
        return $bool;
    }

    //delete 删除
    public function delete(){
        $sql = 'delete from ';
        $sql.=!empty($this->table)?$this->table:die("Table can't be null");
        $sql.=!empty($this->where)?' where '.$this->where:die("Condition cannot be empty");
        $this->sql=$sql;
        $bool = @pg_query($this->pg,$sql);
        $this->clearInfo();
        return $bool;
    }

    //find 查询一条数据
    public function find(){
        $sql = 'select ';
        $sql.=empty($this->field)?'* ':$this->field;
        $sql.=!empty($this->table)?' from '.$this->table:die("Table can't be null");
        $sql.=!empty($this->join)?$this->join:'';
        $sql.=!empty($this->where)?' where '.$this->where:die("Condition cannot be empty");
        $this->sql=$sql;
        $result = @pg_query($this->pg,$sql);
        $str = @pg_fetch_all($result);
        empty($res)?$res = array(array()):'';
        $this->clearInfo();
        return $str[0];
    }

    //query sql语句查询
    public function query($sql){
        $this->clearInfo();
        $result = @pg_query($this->pg,$sql);
        $str = @pg_fetch_all($result);
        return $str;
    }

    //返回sql语句
    public function getsql(){
        return $this->sql;
    }

}
