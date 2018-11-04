<?php
/**********************************************************                                                                  
 *  
 *  FileName:  TagParse.php
 *
 *  Author:  wxreturn
 *
 *  Version:  1.0
 *
 *  DateTime:  2018年11月4日 - 下午4:10:44
 *
 *  Description:  标签解析类基类
 *
 **********************************************************/

namespace rne;

class TagParse
{

    # 闭合的标签  .+\\4：使用 . 是因为参数中可能含有逗号\w不能匹配
    protected $tagRegExpClose = "#<(rne:%TAG%)((\s+\w+\s*=\s*([\"\']).+\\4)+)\s*>(.*)?</\\1>#Us";

    # 非闭合标签
    protected $tagRegExpOpen = "#<(rne:%TAG%)((\s+\w+\s*=\s*([\"\']).+\\4)+)\s*/\s*>#U";
    
    # 标签闭合状态 true闭合，false非闭合
    protected $tagStatus = true;
    
    # 模板内容 
    protected $tempContent = '';
    
    # 标签数据块
    protected $tagDataBlock = [];
        
    # 标签属性
    protected $tagAttr = [];
    
    # 标签数据 捕获组
    protected $tagData = [];
    
    # 数据字段
    protected $dataFields = []; 
    
    # 标签名称 默认list
    protected $tagName = 'list';
    
    # SQL语句 简单查询，以后完善功能
    protected $sqlCommand = 'SELECT %FIELDS% FROM %TABLE% %WHERE% %ORDERBY% %LIMIT%';
    
    # 数据表名称
    protected $tableName = '';
    
    public function __construct()
    {
        $this->tagRegExpClose = str_replace("%TAG%", $this->tagName, $this->tagRegExpClose);
        $this->tagRegExpOpen = str_replace("%TAG%", $this->tagName, $this->tagRegExpOpen);
    }
    
    
    /**
     * 获取标签及其包裹的数据
     */
    public function getDataBlock()
    {
        if($this->tagStatus == true)
        {
            $res = preg_match_all($this->tagRegExpClose, $this->tempContent, $this->tagDataBlock);
        }
        else
        {
            $res = preg_match_all($this->tagRegExpOpen, $this->tempContent, $this->tagDataBlock);
        }
        if($res == false)
        {
            echo "匹配发生错误";
            die;
        }
    }
    
    /**
     * 获取标签属性
     */
    public function getTagAttr()
    {

        # 属性列表数组字符串
        $attrListArrStr = [];
        foreach($this->tagDataBlock[2] as $temp)
        {
           $partten = [
                '#\s+#', # 1 个及1个以上空格替换成一个
                '#\s*=\s*#', # 等号两边的空格删除，只留下一个等号
                '#\s*,\s*#', # 参数逗号左右的空格删除
                '#^\s*#' # 删除起始空格
           ];
           $replace = [
                ' ',
                '=',
                ',',
                ''
           ];
           $attrListArrStr[] = preg_replace($partten, $replace, $temp);
        }
        print_r($attrListArrStr);
        
        # 属性列表数组
        $attrListArr = [];
        foreach($attrListArrStr as $temp)
        {
           $attrListArr[] = explode(' ', $temp);
        }
        print_r($attrListArr);
        
        # 将属性列表的属性名称作为键值
        foreach($attrListArr as $key=>$attrArr)
        {
            foreach($attrArr as $temp)
            {
                $attr = explode('=', $temp);
                $this->tagAttr[ $key ][ $attr[0] ] = $attr[1];
            }
        }
    }
    
    /**
     * 获取需要调用的数据字段
     */
    public function getDataFields()
    {
        
    }
    
    /**
     * 获取分类ID为构造where条件做准备
     */
    public function getClassifyID()
    {
        
    }
    
    /**
     * 构造where条件
     */
    public function where()
    {
        
    }
    
    /**
     * 数据表名称
     */
    public function tableName()
    {
        
    }
    
    /**
     * 限制结果集行数
     */
    public function limit()
    {
        
    }
    
    /**
     * 排序
     */
    public function orderby()
    {
        
    }
    
    /**
     * 生成内容替换模板标签
     */
    public function createContent()
    {
        
    }
    
    /**
     * 用来综合执行函数
     */
    public function parse()
    {
        
        $this->getDataBlock();
        print_r($this->tagDataBlock);
        $this->getTagAttr();
    }
    
    
    
    public function run()
    {
    
    }
    

}
