<?php
/**********************************************************                                                                  
 *  
 *  FileName:  ListTag.php
 *
 *  Author:  wxreturn
 *
 *  Version:  1.0
 *
 *  DateTime:  2018年11月4日 - 下午8:38:22
 *
 *  Description:  列表
 *
 **********************************************************/

namespace rne;

require_once '.\TagParse.php';

use rne\TagParse;

class ListTag extends TagParse
{
    # 定义标签名称
    protected $tagName = 'list';

    
    public function __construct($tempContent)
    {
        # 调用父类构造函数完成标签名称注册
        parent::__construct();
        # 模板内容
        $this->tempContent = $tempContent;
    }
    
    public function dis()
    {
        echo $this->sqlCommand;
    }
}
