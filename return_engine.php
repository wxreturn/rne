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
    /**
     * 解析标签库正则表达式            <rne:lib include="list,menu,position" />
     */ 
    private $libsRegExp = "#<rne:lib\s+include\s*=\s*([\"\'])(.*)\\1\s*/\s*>#U";
    
    /**
     * 最终生成的数据页共享
     * @var string
     */
    private $tempContent = '';
    
    /**
     * 所需要的标签库
     * @var array
     */
    private $tagLibsName = []; 
    
    /**
     * 加载模板文件
     * @param unknown $tempFilePath
     */
    
    /**
     * 导入标记
     */
    private $importTag = '';
    
    public function __construct($tempFilePath)
    {
        $this->tempContent = file_get_contents($tempFilePath);
    }
    
    /**
     * 返回最终生成的模板数据
     * @return string
     */
    public function display()
    {
        return $this->tempContent;
    }

    /**
     * 解析导入标签库字符串
     */
    public function parseTagLibsString()
    {
        $libs = [];
        $res = preg_match_all($this->libsRegExp, $this->tempContent, $libs);
        if ($res == false)
        {
            echo "标签库导入错误，请检查";
            die;
        }
        
        # 记录导入标签库标签
        $this->importTag = $libs[0][0];
        # 去除所有的空格
        $temp = preg_replace("#\s*#", '', $libs[2][0]);
   
        # 以逗号分隔
        $this->tagLibsName = explode(',', $temp);
    }
    
    /**
     * 导入标签库
     */
    public function importTagLibs()
    {
        # 检查是否存在标签库
        if($this->tagLibsName[0] == '')
        {
           echo "没有导入任何标签库，请至少导入一个";
           die;
        }
        
        # 删除标签库
        $this->tempContent = str_replace($this->importTag, '', $this->tempContent);
        #删除空行
        $this->tempContent = preg_replace("#^\r\n#", '', $this->tempContent);
       
        foreach($this->tagLibsName as $tagLibName)
        {
            $tagLibName = ucfirst($tagLibName);
            
            // 判断文件是否存在
            $tagLibPath = "./" . $tagLibName . 'Tag.php'; 
            if(!file_exists($tagLibPath))
            {
                echo basename($tagLibPath) . " 标签库不存在，请检查";
                die;
            }
            // 导入标签库
            require_once($tagLibPath);
            // 构建类名
            $className = "\\rne\\" . $tagLibName . 'Tag';
            // 生成标签库对象
            $obj = new $className($this->tempContent);
            // 执行解析过程
            $this->tempContent =  $obj->parse();
        }
    }
    
    public function parseHTML()
    {
        $this->parseTagLibsString();
        $this->importTagLibs();
        return $this->tempContent;
    }


}

