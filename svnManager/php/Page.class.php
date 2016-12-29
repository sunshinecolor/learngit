<?php
/**
 * 
 *   1对分页进行初始化【总记录数，显示条数，页数，起始位置, 当前页】 
 *   2 上一页   
 *   3 下一页   
 *   4 打印页码 
 * 
 * 
 */

class Page{
	
	public $total;//总记录数
	public $show;//显示条数
    public $pageNums;//总页数
    public $start;//起始位置
    public $page;//当前页
    public $limit;//每页显示的信息 
    	
	public function __construct($total,$show){
		$this->total = $total;
		$this->show = $show;
		$this->pageNums = ceil($this->total/$this->show);
		empty($total)?$s=0:$s=1; 
		$this->page = empty($_GET['page'])?$s:$_GET['page'];
		$this->start = empty($this->page)? 0 :($this->page-1)*$this->show;
		$this->limit =" limit ".$this->start.",".$this->show;
	}
	
	public function prePage($page){
		if($page>1){
			return $page-1;
		}else{
			return 1;
		}
	}
	
	public function nextPage($page,$pageNums){
		if($page<$pageNums){
			return $page+1;
		}else{
			return $pageNums;
		}
	}
	
    public function pageInfo($page,$strDatax=null){
    	
    	if(empty($strDatax)){
	    	$strData = "<ul class='page clearfix'>";
	    	$strData.="<li><a class='grey' href='?page=1'>首页</a></&nbsp;&nbsp;</li>";
	    	$strData.="<li><a class='grey' href='?page=".$this->prePage($page)."'>上一页</a>&nbsp;&nbsp;</li>";
	    	$strData.="<li><a class='grey' href='?page=".$this->nextPage($page, $this->pageNums)."'>下一页</a>&nbsp;&nbsp;</li>";
	    	$strData.="<li><a class='grey' href='?page=".$this->pageNums."'>尾页</a>&nbsp;&nbsp;</li>";
	    	$strData.="<li class='grey'>第".$page."页/共".$this->pageNums."页&nbsp;&nbsp;&nbsp;</li>";
	    	$strData.="<li class='lastli grey'>共".$this->total."条记录</li>";
	    	$strData.="</ul>";
    	}else{
            $strData = "<ul class='page clearfix'>";
    		$strData.="<li><a class='grey' href='?page=1".$strDatax."'>首页</a>&nbsp;&nbsp;</li>";
    		$strData.="<li><a class='grey' href='?page=".$this->prePage($page).$strDatax."'>上一页</a>&nbsp;&nbsp;</li>";
    		$strData.="<li><a class='grey' href='?page=".$this->nextPage($page, $this->pageNums).$strDatax."'>下一页</a>&nbsp;&nbsp;</li>";
    		$strData.="<li><a class='grey' href='?page=".$this->pageNums.$strDatax."'>尾页</a>&nbsp;&nbsp;&nbsp;&nbsp;</li>";
    		$strData.="<li class='grey'>第".$page."页/共".$this->pageNums."页&nbsp;&nbsp;&nbsp;</li>";
    		$strData.="<li class='lastli grey'>共".$this->total."条记录</li>";
            $strData.="</ul>";
    	}
    	return $strData;
    }	
	
}
