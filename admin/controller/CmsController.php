<?php


namespace app\controller;

use app\logic\DictionarryLogic;
use app\model\BaseModel;
use app\model\CmsCategoryModel;
use app\model\CmsModelModel;
use app\model\CmsPostModel;
use app\model\CmsPageModel;
use app\model\CmsTagModel;
use app\model\CoreTextModel;
use houduanniu\base\BosonNLP;
use houduanniu\web\Form;
use houduanniu\base\Page;
use Overtrue\Pinyin\Pinyin;
use app\model\CmsCategorysModel;

/**
 * 内容管理控制器
 * @privilege 内容管理|Admin/Cms|e902296d-2006-11e7-8ad5-9cb3ab404081|1
 * @date 2016年5月4日 21:17:23
 * @author Administrator
 */
class CmsController extends UserBaseController
{

    /**
     * 添加栏目
     * @privilege 添加栏目|Admin/Cms/addColumn|e90e8dd1-2006-11e7-8ad5-9cb3ab404081|2
     */
    public function addColumn()
    {
        if (IS_POST) {
            $logic = new DictionarryLogic();
            $request_data = $logic->getRequestData('cms_categorys', 'table');
            $model = new CmsCategorysModel();
            $result = $model->addCategory($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            #表单数据
            {
                $dictionaryLogic = new DictionarryLogic();
                $form_init = $dictionaryLogic->getFormInit('cms_categorys', 'table');
            }
            #自定义枚举值
            {
                #栏目分类
                $cmsCategoryModel = new CmsCategorysModel();
                $category_result = $cmsCategoryModel->orm()->select_expr('id,pid,category_name')->find_array();
                $category_list = treeStructForLevel($category_result);
                $enum = [];
                foreach ($category_list as $key => $value) {
                    $enum[] = [
                        'value' => $value['id'],
                        'option' => $value['placeHolder'] . $value['category_name'],
                    ];
                }
                $form_init['pid']['enum'] = $enum;

                #模型分类
                $cmsModelModel = new CmsModelModel();
                $model_result = $cmsModelModel->getAllCmsModel('id,name');
                $enum = [];
                foreach ($model_result as $key => $value) {
                    $enum[] = [
                        'value' => $value['id'],
                        'option' => $value['name'],
                    ];
                }
                $form_init['model_id']['enum'] = $enum;
            }
            #获取数据(编辑使用)
            {
                $result = [];
                if ($id) {
                    $result = $cmsCategoryModel->getCateInfoById($id);
                }
            }

            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '添加栏目' => ''
            ));
            $this->display('Cms/addColumn');
        }
    }

    /**
     * 异步处理插件
     * @privilege 异步处理插件|Admin/Cms/ajaxPlug|f7effdf6-775f-11e7-ba80-5996e3b2d0fb|3
     */
    public function ajaxPlug()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $class_name = $_POST['class'];
        $method_name = $_POST['method'];
        if (!class_exists($class_name)) {
            $this->ajaxFail('处理对象不存在');
        }
        if (!method_exists($class_name, $method_name)) {
            $this->ajaxFail('方法不存在');
        }
        $param = isset($_POST['param']) ? $_POST['param'] : [];
        $result = call_user_func_array([new $class_name, $method_name], $param);
        if (!$result) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess('执行成功', $result);
        }
    }


    public function addPost()
    {
        if (IS_POST) {
            #获取模型信息
            $model_id = isset($_POST['model_id']) ? intval($_POST['model_id']) : 0;
            $cmsModelModel = new CmsModelModel();
            $model_result = $cmsModelModel->getRecordById($model_id);
            if (!$model_result) {
                $this->ajaxSuccess('内容模型不存在');
            }
            $model_name = $model_result['value'];
            $logic = new DictionarryLogic();
            $request_data = $logic->getRequestData($model_name, 'model');
            $model = new CmsPostModel();
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail('文档添加失败,' . $this->getMessage());
            } else {
                $this->ajaxSuccess('文档添加成功');
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

            #获取文档信息
            $post_result = [];
            $cmsPostModel = new CmsPostModel();
            if ($id) {
                $post_result = $cmsPostModel->getRecordById($id);
            }

            #获取栏目信息
            $category_result = [];
            $cmsCategoryModel = new CmsCategorysModel();
            if ($category_id) {
                $category_result = $cmsCategoryModel->getRecordById($category_id);
            }

            if (!$post_result && !$category_result) {
                die('请选择栏目');
            }

            #获取模型信息
            $model_id = $post_result ? $post_result['model_id'] : $category_result['model_id'];
            if (!$model_id) {
                die('请选择内容模型');
            }
            $cmsModelModel = new CmsModelModel();
            $model_result = $cmsModelModel->getRecordById($model_id);
            if (!$model_result) {
                die('内容模型不存在');
            }

            #获取表单初始化数据
            $model_name = $model_result['value'];
            $dictionaryLogic = new DictionarryLogic();
            $form_init = $dictionaryLogic->getFormInit($model_name, 'model');
            #完善表单枚举数据
            {
                $all_category_result = $cmsCategoryModel->getAllRecord();
                $list = treeStructForLevel($all_category_result);
                $enum = [];
                foreach ($list as $key => $value) {
                    $enum[] = [
                        'value' => $value['id'],
                        'option' => $value['placeHolder'] . $value['category_name'],
                    ];
                }
                $form_init['category_id']['enum'] = $enum;
            }

            #添加文档是默认数据
            if (empty($post_result)) {
                $post_result['category_id'] = $category_id;
                $post_result['model_id'] = $model_result['id'];
                $post_result['post_id'] = getItemId();
            }

            Form::getInstance()->form_data($post_result)
                ->form_schema($form_init);

            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '添加文档' => ''
            ));
            $this->display($model_result['post_add_template']);
        }
    }

    /**
     * 栏目列表
     * @privilege 栏目列表|Admin/Cms/index|e91d2442-2006-11e7-8ad5-9cb3ab404081|2
     */
    public function index()
    {
        #获取栏目列表数据
        $cmsCategoryModel = new CmsCategorysModel();
        $all_category_result = $cmsCategoryModel->getAllRecord();
        $list = treeStructForLevel($all_category_result);
        #获取列表字段
        $dictionarylogic = new DictionarryLogic();
        $list_init = $dictionarylogic->getListInit('cms_categorys');
        #完善列表字段枚举值
        {
            #父级栏目
            $enum = [];
            foreach ($all_category_result as $value) {
                $enum[$value['id']] = $value['category_name'];
            }
            $enum[0] = '根目录';
            $list_init['pid']['enum'] = $enum;
            #栏目模型
            $enum = [];
            $cmsModelModel = new CmsModelModel();
            $model_result = $cmsModelModel->getAllRecord();
            foreach ($model_result as $value) {
                $enum[$value['id']] = $value['name'];
            }
            $list_init['model_id']['enum'] = $enum;
        }
        $data = array(
            'list' => $list,
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '内容管理' => U('Cms/index'),
            '栏目管理' => ''
        ));
        $this->display('Cms/index', $data);
    }

    /**
     * 删除目录
     * @privilege 删除目录|Admin/Cms/delColumn|e92a1a4e-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function delColumn()
    {
        $model = new CmsCategoryModel();
        #验证
        $rule = array(
            'id' => 'required',
        );
        $attr = array(
            'id' => '栏目ID',
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (!$model->deleteColumn($id)) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess('删除成功');
        }
    }

    /**
     * 文章列表
     * @privilege 文章列表|Admin/Cms/articleList|c68ffb0f-2008-11e7-8ad5-9cb3ab404081|2
     */
    public function articleList()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0; #栏目id
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1; #当前页
        $fetch_row = 20; #每页条数
        #获取列表字段
        $dictionaryLogic = new DictionarryLogic();
        $list_init = $dictionaryLogic->getListInit('cms_posts');
        #获取列表数据
        $model = new CmsPostModel();
        #统计记录数
        $count = $model->getRecordList('', '', '', TRUE);
        #分页
        $page = new Page($count, $p, $fetch_row);
        $list = $model->getRecordList('', $page->getOffset(), $fetch_row, FALSE);
        $data = array(
            'list' => $list,
            'list_init' => $list_init,
            'page' => $page->getPageStruct(),
        );
        #面包屑导航
        $this->crumb(array(
            '内容管理' => U('Cms/index'),
            '文章管理' => ''
        ));
        $this->display('Cms/articleList', $data);
    }

    /**
     * 文档列表
     * @privilege 文档列表|Admin/Cms/postList|c091f245-768b-11e7-ba80-5996e3b2d0fb|3
     */
    public function postList()
    {
        $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0; #栏目id
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1; #当前页
        $fetch_row = 20; #每页条数

        if (!$category_id) {
            die('栏目列表不能为空');
        }

        #获取列表数据
        $model = new CmsPostModel();
        $orm = $model->orm()->where('category_id', $category_id);
        #统计记录数
        $count = $model->getRecordList($orm, '', '', TRUE);
        #分页
        $page = new Page($count, $p, $fetch_row);
        $list = $model->getRecordList($orm, $page->getOffset(), $fetch_row, FALSE);
        #获取列表字段
        $dictionaryLogic = new DictionarryLogic();
        $list_init = $dictionaryLogic->getListInit('cms_posts');
        #完善列表字段枚举值
        {
            #父级栏目
            $cmsCategoryModel = new CmsCategorysModel();
            $all_category_result = $cmsCategoryModel->getAllRecord();
            $enum = [];
            foreach ($all_category_result as $value) {
                $enum[$value['id']] = $value['category_name'];
            }
            $enum[0] = '根目录';
            $list_init['category_id']['enum'] = $enum;
            #栏目模型
            $enum = [];
            $cmsModelModel = new CmsModelModel();
            $model_result = $cmsModelModel->getAllRecord();
            foreach ($model_result as $value) {
                $enum[$value['id']] = $value['name'];
            }
            $list_init['model_id']['enum'] = $enum;
        }
        $data = array(
            'list' => $list,
            'list_init' => $list_init,
            'page' => $page->getPageStruct(),
        );
        #面包屑导航
        $this->crumb(array(
            '内容管理' => U('Cms/index'),
            '文档列表' => ''
        ));

        $this->display('Cms/postList', $data);
    }

    protected function getImageFromContent($content)
    {
        //匹配IMG标签
        $content = htmlspecialchars_decode($content);
        $img_pattern = "/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i";
        preg_match_all($img_pattern, $content, $img_out);
        return $img_out[2];
    }

    protected function getImageUrlFromUrl($url)
    {
        $_url = explode('/', $url);
        return end($_url);
    }

    /**
     * 添加文章
     * @privilege 添加文章|Admin/Cms/addArticle|c69c73ec-2008-11e7-8ad5-9cb3ab404081|3
     */
    public function addArticle()
    {
        if (IS_POST) {
            $model = new CmsPostModel();
            #验证
            $rule = array(
                'title' => 'required',
                'column_id' => 'required|integer',
            );
            $attr = array(
                'title' => '标题名称',
                'column_id' => '栏目ID',
            );
            $validate = $model->validate()->make($_POST, $rule, [], $attr);
            if (false === $validate->passes()) {
                $this->ajaxFail($validate->messages()->first());
            }
            #获取参数
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $editor = isset($_POST['editor']) ? trim($_POST['editor']) : '';
            $imageUrl = isset($_POST['image_name']) ? trim($_POST['image_name']) : '';
            $columnId = isset($_POST['column_id']) ? intval($_POST['column_id']) : 0;
            $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $content = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
            $isRecommend = isset($_POST['is_recommend']) ? intval($_POST['is_recommend']) : 0;
            $isTop = isset($_POST['is_top']) ? intval($_POST['is_top']) : 0;
            $publicTime = (isset($_POST['public_time']) && strtotime($_POST['public_time']) > 0) ? date('Y-m-d H:i:s', strtotime($_POST['public_time'])) : date('Y-m-d H:i:s', time());
            $tagName = isset($_POST['tag_name']) && !empty($_POST['tag_name']) ? explode(',', trim($_POST['tag_name'])) : [];
            $titleAlias = isset($_POST['title_alias']) ? htmlspecialchars(trim($_POST['title_alias'])) : '';
            #缩略图为空 取文章图片为缩略图
            if (!$imageUrl) {
                $img = $this->getImageFromContent($content);
                if ($img) {
                    $imageUrl = $this->getImageUrlFromUrl(current($img));
                }
            }
            $isImage = $imageUrl ? 10 : 0;
            if (!$titleAlias) {
                $pinyin = new Pinyin();
                $titleAlias = htmlspecialchars(join('-', $pinyin->convert($title)));
            }
            $model->beginTransaction();
            try {
                #创建文章主表记录
                $data = array(
                    'id' => $id,
                    'title' => $title,
                    'editor' => $editor,
                    'image_name' => $imageUrl,
                    'column_id' => $columnId,
                    'content' => $content,
                    'public_time' => $publicTime,
                    'keyword' => $keyword,
                    'description' => $description,
                    'is_top' => $isTop,
                    'is_recommend' => $isRecommend,
                    'is_image' => $isImage,
                    'title_alias' => $titleAlias
                );
                $postId = $model->addPost($data);
                if (!$postId) {
                    throw new \Exception('文章内容添加失败');
                }
                #文章标签操作
                {
                    $cmsTagModel = new CmsTagModel();
                    $addTagIdIn = [];
                    #添加标签
                    if (!empty($tagName)) {
                        foreach ($tagName as $val) {
                            $tagId = $cmsTagModel->addTag(['tag_name' => $val]);
                            $addTagIdIn[] = $tagId;
                            if (!$tagId) {
                                throw new \Exception('添加文章标签失败');
                            }
                            if (!$cmsTagModel->addTagPost($tagId, $postId)) {
                                throw new \Exception('添加文章标签关联失败');
                            }
                        }
                    }
                    #删除文章标签关联
                    if (!$cmsTagModel->delTagPost($postId, $addTagIdIn)) {
                        throw new \Exception('删除文章标签关联失败');
                    }
                }

                $model->commit();
            } catch (\Exception $ex) {
                $model->rollBack();
                $this->ajaxFail($ex->getMessage());
            }
            $this->ajaxSuccess('文章添加成功');
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $columnId = isset($_GET['column_id']) ? intval($_GET['column_id']) : 0;
            $info = array(
                'id' => $id,
                'title' => '',
                'description' => '',
                'keyword' => '',
                'public_time' => '',
                'editor' => $this->getInfo('loginInfo')['true_name'],
                'created' => '',
                'image_name' => '',
                'column_id' => $columnId,
                'click' => '',
                'is_publish' => '',
                'content' => '',
                'thumb' => '',
                'is_top' => 0,
                'is_recommend' => 0,
                'is_image' => 0,
                'title_alias' => ''
            );
            if ($id) {
                $articleModel = new CmsPostModel();
                $info = $articleModel->getArticleInfoById($id);
                $info['thumb'] = $info['image_name'] ? getImage($info['image_name']) : '';
            }
            #栏目列表
            $columnModel = new CmsCategoryModel();
            $list = $columnModel->getColumnList(['where' => ['cate_type', $columnModel::CATE_TYPE_LIST]], 'id,pid,name');
            $data = array(
                'list' => $columnModel::unlimitedForLevel($list, '|__'),
                'info' => $info,
            );
            #获取tag列表
            {
                $CmsTagModel = new CmsTagModel();
                $result = $CmsTagModel->getTagList(0, 999, false, 'tag_id,tag_name');
                $data['tag_list'] = $result;
                $postTag = $CmsTagModel->getTagByPostId($id);
//                $postTag=array_column($postTag,'tag_name');
                $data['post_tag'] = $postTag;
            }
            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '添加文章' => ''
            ));
            $this->display('Cms/addArticle', $data);
        }
    }

    /**
     * 删除文章
     * @privilege 删除文章|Admin/Cms/delArticle|c6a7aa7b-2008-11e7-8ad5-9cb3ab404081|3
     */
    public function delArticle()
    {
        if (IS_POST) {
            $model = new BaseModel();
            #验证
            $rule = array(
                'id' => 'required|array',
            );
            $attr = array(
                'id' => '文章ID',
            );
            $validate = $model->validate()->make($_POST, $rule, [], $attr);
            if (false === $validate->passes()) {
                $this->ajaxFail($validate->messages()->first());
            }
            #获取参数
            $id = $_POST['id'];

            $success = 0;
            foreach ($id as $v) {
                if ($this->delArticles($v)) {
                    $success++;
                }
            }
            if ($success != count($id)) {
                $this->ajaxFail($this->getMessage());
            }
            $this->ajaxSuccess('删除成功');
        }
    }

    protected function delArticles($id)
    {
        $model = new CmsPostModel();
        return $model->deleteRecordById($id);
    }


    /**
     * 添加标签
     * @privilege 添加标签|Admin/Cms/addTag|c6b424e9-2008-11e7-8ad5-9cb3ab404081|3
     */
    public function addTag()
    {
        if (IS_POST) {
            $rules = array(
                'tag_name' => 'required',
                'tag_description' => 'required',
            );
            $attr = array(
                'tag_name' => '标签名称',
                'tag_description' => '标签描述'
            );
            $model = new CmsTagModel();
            $validator = $model->validate()->make($_POST, $rules, $attr);
            if (!$validator->passes()) {
                $this->ajaxFail($validator->messages()->first());
            }
            $tagId = isset($_POST['tag_id']) ? intval($_POST['tag_id']) : 0;
            $tagName = isset($_POST['tag_name']) ? trim($_POST['tag_name']) : '';
            $tagDescription = isset($_POST['tag_description']) ? trim($_POST['tag_description']) : '';
            $tagSort = isset($_POST['tag_sort']) ? intval($_POST['tag_sort']) : 100;
            $data = array(
                'tag_id' => $tagId,
                'tag_name' => $tagName,
                'tag_description' => $tagDescription,
                'tag_sort' => $tagSort,
            );
            $result = $model->addTag($data);
            if (!$result) {
                $this->ajaxFail('标签添加失败,' . $this->getMessage());
            } else {
                $this->ajaxSuccess('标签添加成功');
            }
        } else {
            $tagId = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $model = new CmsTagModel();
            $data = array(
                'tag_id' => $tagId,
                'tag_name' => '',
                'tag_description' => '',
                'tag_sort' => 100
            );
            if (!empty($tagId)) {
                $result = $model->orm()
                    ->find_one($tagId);
                if ($result) {
                    $data = $result->as_array();
                }
            }
            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '添加标签' => ''
            ));
            $info = array(
                'data' => $data
            );
            $this->display('Cms/addTag', $info);
        }
    }

    /**
     * 标签管理
     * @privilege 标签管理|Admin/Cms/tag|c6c0d2cc-2008-11e7-8ad5-9cb3ab404081|2
     */
    public function tag()
    {
        #面包屑导航
        $this->crumb(array(
            '内容管理' => U('Cms/index'),
            '标签管理' => ''
        ));
        $this->display('Cms/tag');
    }

    /**
     * 生成标签
     * @privilege 生成标签|Admin/Cms/generateTag|01df5951-7816-11e7-ba80-5996e3b2d0fb|3
     */
    public function generateTag()
    {
        $cmsTagModel = new CmsTagModel();
        $cmsAttributeModel = $cmsTagModel->orm()->for_table('cms_post_extend_attribute')->use_id_column('id');
        $cmsPostTagModel = $cmsTagModel->orm()->for_table('cms_post_tag')->use_id_column('id');
        $cmsTagModel->orm()->delete_many();
        $cmsPostTagModel->delete_many();
        $post_tag_result = $cmsAttributeModel->table_alias('a')
            ->select_expr('a.*')
            ->left_join('cms_posts', ['a.post_id', '=', 'p.post_id'], 'p')
            ->where('p.deleted', 0)
            ->where('a.field', 'post_tag')
            ->find_array();

        foreach ($post_tag_result as $value) {
            $tag_list = explode(',', $value['value']);
            $post_id = $value['post_id'];
            foreach ($tag_list as $val) {
                $tag_info = $cmsTagModel->orm()->where('tag_name', $val)->find_one();
                if (empty($tag_info)) {
                    $data = [
                        'tag_name' => $val,
                    ];
                    $tag_id = $cmsTagModel->addRecord($data);
                } else {
                    $tag_id = $tag_info->id();
                }
                $cmsPostTagModel = $cmsTagModel->orm()->for_table('cms_post_tag')->use_id_column('id');
                $post_tag_info = $cmsPostTagModel->where('tag_id', $tag_id)->where('post_id', $post_id)->find_one();
                if (empty($post_tag_info)) {
                    $data = [
                        'tag_id' => $tag_id,
                        'post_id' => $post_id,
                    ];
                    $tag_id = $cmsPostTagModel->create($data)->save();
                }
            }
        }
    }

    /**
     * 文章分词
     * @privilege 文章分词|Admin/Cms/ajaxFenci|c6ce19bb-2008-11e7-8ad5-9cb3ab404081|3
     */
    public function ajaxFenci()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $text = isset($_POST['content']) ? trim(strip_tags($_POST['content'])) : '';
        if (empty($text)) {
            $this->ajaxFail('源数据不能为空');
        }
        $token = $this->siteInfo['cfg_BosonNLP_TOKEN'];
        if (empty($token)) {
            $this->ajaxFail('请先设置玻森分词api Token');
        }
        $fenci = new BosonNLP($token);
        $tagModel = new CmsTagModel();
        //提取关键字
        $pram = [
            'top_k' => 10,
        ];
        $result = $fenci->analysis($fenci::ACTION_KEYWORDS, $text, $pram);
        if (!$result) {
            $this->ajaxFail('分词失败');
        }
        $keyword = [];
        foreach ($result[0] as $key => $val) {
            $keyword[] = $val[1];
        }
        //提取描述
        $data = [
            'content' => $text,
            'not_exceed' => 0,
            'percentage' => 0.1,
        ];
        $result = $fenci->analysis($fenci::ACTION_SUMMARY, $data);
        $summary = !empty($result) ? str_replace(PHP_EOL, "", $result) : '';
        $return = [
            'keyword' => join(',', $keyword),
            'tag' => join(',', array_slice($keyword, 0, 5)),
            'description' => $summary,
        ];
        $this->ajaxSuccess('获取成功', $return);
    }

}
