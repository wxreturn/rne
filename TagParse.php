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
    protected $tagRegExpOpen = "#<(rne:%TAG%)((\s+\w+\s*=\s*([\"\']).+\\4)+)\s*/\s*>#Us";
    
    # 标签闭合状态 true闭合，false非闭合
    protected $tagStatus = true;
    
    # 模板内容 
    protected $tempContent = '';
    
    # 标签数据块
    protected $tagDataBlock = [];
    
    # 标签数据 捕获组
    protected $tagData = [];
    
    # 标签属性
    protected $tagFields = [];
    
    # 数据字段
    protected dataFields = []; 
    
    # 标签名称
    protected $tagName = 'list';
    
    # SQL语句 简单查询，以后完善功能
    protected $sqlCommand = 'SELECT %FIELDS% FROM %TABLE% %WHERE% %LIMIT%';
    
    # 标签参数列表默认属性
    protected $tagAttr = [
        'limit' => '0, 10'
    ];
    
    public function __construct()
    {
        $this->tagRegExpClose = str_replace("%TAG%", $this->tagName, $this->tagRegExpClose);
        $this->tagRegExpOpen = str_replace("%TAG%", $this->tagName, $this->tagRegExpOpen);
    }
    
    public function parse()
    {
//         // 读取文本内容
//         $tempFilePath = '.\template.html';
//         $subject = file_get_contents($tempFilePath);
//         echo $partten = $this->tagRegExpClose;
//         $match = '';
        
//         preg_match_all($partten, $subject, $match);
//         print_r($match);
    }
    
    public function run()
    {
    
    }
    

}

?>