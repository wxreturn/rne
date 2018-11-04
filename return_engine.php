<?php
/**********************************************************                                                                  
 *  
 *  FileName:  return_engine.php
 *
 *  Author:  wxreturn
 *
 *  Version:  1.0
 *
 *  DateTime:  2018年11月4日 - 下午8:30:02
 *
 *  Description:  智能模板引擎调
 *
 **********************************************************/

namespace rne;

class ReturnEngine
{
    # 解析标签库正则表达式
    private $libsRegExp = "#<rne:lib\s*=\s*([\"\']).*(\\1)\s*/\s*>#U";
    
    # 最终生成的数据页共享
    private $tempContent = '<rne:lib="list,menu,position" />';
    
    # 所需要的标签库
    private $tagLibsName = []; 
    
    # 加载模板文件
    public function __construct($tempFilePath)
    {
        $this->tempContent = file_get_contents($tempFilePath);
    }
    
    # 返回数据文件
    public function display()
    {
        return $this->tempContent;
    }

    # 解析标签库 <rne:lib="list,menu,position" />
    private function parseTagLibs()
    {
        $res = preg_match_all($this->libsRegExp, $this->tempContent, $this->tagLibsName);
        if ($res == false)
        {
            echo "标签库导入错误，请检查";
            return;
        }
        else
        {
            print_r($res);
        }
    }
    
    
}