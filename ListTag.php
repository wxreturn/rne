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

    # 定义标签支持的字段
    protected $usableFields = [
        'id',                 # 文章ID
        'bid',                # 文章所属分类ID 
        'author',             # 文章作者
        'describe',           # 文章描述
        'keyword',            # 文章关键词
        'image',              # 文章封面图片
        'title',              # 文章标题
        'datetime'                # 文章日期
    ];
    
    
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
