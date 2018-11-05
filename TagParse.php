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
    
    # 字段正则                                               <rne:fields.title function="函数名称"/ ><rne:fields.content /><rne:fields.image />
    protected $tagFieldsRegExp = "#<rne:fields.(%FIELDS%)(\s+function\s*=\s*([\"'])(.+)\\3)?\s*/\s*>#U";
    
    # 标签闭合状态 true闭合，false非闭合
    protected $tagStatus = true;
    
    # 模板内容 
    protected $tempContent = '';
    
    # 标签数据块 数组索引0,完整标签内容; 1,捕获组列表库及标签名称; 2,属性列表字符串; 3,列表属性最后一个(无用); 4,匹配的引号(无用); 5,含有字段的文本
    protected $tagDataBlock = [];
        
    # 标签属性
    protected $tagAttr = [];
    
    # 标签默认支持的字段
    protected $usableFields = ['\w+'];
    
    # 标签字段数据 包含所有的信息
    protected $tagFieldsData = [];
    
    # 标签名称 默认list
    protected $tagName = 'list';
    
    # SQL语句 简单查询，以后完善功能
    protected $sqlCommand = 'SELECT %FIELDS% FROM %TABLE% %WHERE% %ORDERBY% %LIMIT%';
    
    # 数据表名称
    protected $tableName = '';
    
    # 数据连接句柄
    protected $mysqli = null;
    
    public function __construct()
    {
        # 正则表达式具体化
        if ($this->tagStatus == true)
        {
            $this->tagRegExpClose = str_replace("%TAG%", $this->tagName, $this->tagRegExpClose);
            
            $tagFieldsString = implode('|', $this->usableFields);
            $this->tagFieldsRegExp = str_replace("%FIELDS%", $tagFieldsString, $this->tagFieldsRegExp);
        }
        else 
        {
            $this->tagRegExpOpen = str_replace("%TAG%", $this->tagName, $this->tagRegExpOpen);
        }
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
                '#\s+#',             # 1 删除连续两个及以上空格
                '#\s*=\s*#',         # 2 等号两边的空格删除，只留下一个等号
                '#\s*,\s*#',         # 3 删除属性值逗号左右的空格删除
                '#^\s*#',            # 4 删除起始空格
                '#\s*$#',            # 5 删除末尾空格
                '#=([\'\"])\s+#',    # 6 引号右边的空格
                '#\s+([\'\"])#',     # 7 引号左边的空格
                '#[\'\"]#'           # 8 删除引号
           ];
           $replace = [
                ' ',                 # 1
                '=',                 # 2
                ',',                 # 3
                '',                  # 4
                '',                  # 5
                '=\\1',              # 6
                '\\1',               # 7
                ''                   # 8
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
        print_r($this->tagAttr);
    }
    
    /**
     * 获取需要调用的数据字段, 格式同$this->tagAttr
     */
    public function getTagFieldsData()
    {
        // 闭合标签，非闭合标签什么也不做例如<br />
        if($this->tagStatus != true)
        {
            return;
        }
        
        foreach($this->tagDataBlock[5] as $key=>$data)
        {
            $res = preg_match_all($this->tagFieldsRegExp, $data, $this->tagFieldsData[$key]);
            if($res === false)
            {
                echo "数据字段匹配失败，请检查";
                die;
            }
        }        
        print_r($this->tagFieldsData);
        
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
    public function where($attr)
    {
        $res = 'where bid ';
        if( isset($attr['bid']) )
        {
            $res .= 'in(' . preg_replace("#\s+#", '', $attr['bid']) . ')';
        }
        else 
        {
            $res .= " = 0";
        }
        return $res;
        
    }
    
    /**
     * 数据表名称
     */
    public function tableName()
    {
        return "`rne_article`";
    }
    
    /**
     * 限制结果集行数
     */
    public function limit($attr)
    {
        $res = 'limit ';
        if( isset($attr['limit']) )
        {
            $res .=  preg_replace("#\s+#", '', $attr['limit']);
        }
        else
        {
            $res = 'limit 0, 5';
        }
        return $res;
    }
    
    /**
     * 排序
     */
    public function orderby($attr)
    {
        $res = 'order by ';
        if( isset($attr['orderby']) )
        {
            $res .=  preg_replace("#\s*,\s*#", ' ', $attr['orderby']);
        }
        else
        {
            $res = 'orderby id desc';
        }
        return $res;
    }
    
    /**
     * 查询字段
     */
    public function fields($fields)
    {
        $res = '';
        if( empty($fields) == true)
        {
            $res = ' * ';
        }
        else 
        {
            foreach($fields as $field)
            {
                $res .= '`' . $field . '`' . ', ';
            }    
        }
        # 删除最后一个逗号
        $res = preg_replace("#,\s+$#", '', $res);
        return $res;
    }
    
    /**
     * 分析完所有的属性以及需要的数据字段以后就需要组合SQL执行SQL语句，然后获取内容替换标记完成数据内容的生成工作
     */
    
    /**
     * 
     */
    
    
    /**
     * 生成内容替换模板标签
     */
    public function createContent()
    {
       foreach($this->tagAttr as $key=>$attr)
       {
           $sqlCommand = $this->commbinSQL(
               $this->fields($this->tagFieldsData[ $key ][ 1 ]), 
               $this->tableName(), 
               $this->where($attr),
               $this->orderby($attr),
               $this->limit($attr)
           );
       }
    }
    
    /**
     * 
     */
    public function commbinSQL($fields, $table, $where, $orderby, $limit)
    {
        // 'SELECT %FIELDS% FROM %TABLE% %WHERE% %ORDERBY% %LIMIT%';
        $sqlCommand = $this->sqlCommand;
        $search = [
            '%FIELDS%',
            '%TABLE%',
            '%WHERE%',
            '%ORDERBY%',
            '%LIMIT%'
        ];
        $replace = [
            $fields,
            $table,
            $where,
            $orderby,
            $limit
        ];
        
        echo $sqlCommand = str_replace($search, $replace, $sqlCommand);
        echo "<br/>";
        return $sqlCommand;
    }
    
    /**
     * 用来综合执行函数
     */
    public function parse()
    {
        
        $this->getDataBlock();
        print_r($this->tagDataBlock);
        $this->getTagAttr();
        $this->getTagFieldsData();
        $this->SQL();
        $this->createContent();
    }
    
    
    
    public function run()
    {
    
    }
    
    /**
     * 数据库操作
     */
    public function SQL()
    {
        $CONF = require_once('.\config.php');
        $mysqli = new \mysqli($CONF['hostname'], $CONF['username'], $CONF['password'], $CONF['dbname']);
        $mysqli->query("set names utf8");
        
//         $res = $mysqli->query("select * from rne_classify;");
//         while($row = $res->fetch_row())
//         {
//             print_r($row);
//         }
    }

}
