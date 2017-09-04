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
use houduanniu\base\Application;
use houduanniu\base\BosonNLP;
use houduanniu\base\Hook;
use houduanniu\web\Form;
use houduanniu\base\Page;
use Overtrue\Pinyin\Pinyin;

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
     * @privilege 添加栏目|Admin/Cms/addCategory|e90e8dd1-2006-11e7-8ad5-9cb3ab404081|2
     */
    public function addCategory()
    {
        if (IS_POST) {
            $logic = new DictionarryLogic();
            $request_data = $logic->getRequestData('cms_category', 'table');
            $model = new CmsCategoryModel();
            $result = $model->addRecord($request_data);
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
                $form_init = $dictionaryLogic->getFormInit('cms_category', 'table');
            }
            #自定义枚举值
            {
                #栏目分类
                $cmsCategoryModel = new CmsCategoryModel();
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
                    $result = $cmsCategoryModel->getRecordInfoById($id);
                }
            }

            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '添加栏目' => ''
            ));
            $this->display('Cms/addCategory');
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

    /**
     * 添加文档
     * @privilege 添加文档|Admin/Cms/addPost|f7effdf6-776f-11e7-ba80-5996e3b2d0fb|3
     */
    public function addPost()
    {
        if (IS_POST) {
            #获取模型信息
            $model_id = isset($_POST['model_id']) ? intval($_POST['model_id']) : 0;
            $cmsModelModel = new CmsModelModel();
            $model_result = $cmsModelModel->getRecordInfoById($model_id);
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
                $post_result = $cmsPostModel->getRecordInfoById($id);
            }
            #获取栏目信息
            $category_result = [];
            $cmsCategoryModel = new CmsCategoryModel();
            if ($category_id) {
                $category_result = $cmsCategoryModel->getRecordInfoById($category_id);
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
            $model_result = $cmsModelModel->getRecordInfoById($model_id);
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
        $cmsCategoryModel = new CmsCategoryModel();
        $all_category_result = $cmsCategoryModel->getAllRecord();
        $list = treeStructForLevel($all_category_result);
        #获取列表字段
        $dictionarylogic = new DictionarryLogic();
        $list_init = $dictionarylogic->getListInit('cms_category');
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
     * @privilege 删除目录|Admin/Cms/delCategory|e92a1a4e-2006-11e7-8ad5-9cb3ab404081|3
     */
    public function delCategory()
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
        $list_init = $dictionaryLogic->getListInit('cms_post');
        #完善列表字段枚举值
        {
            #父级栏目
            $cmsCategoryModel = new CmsCategoryModel();
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
            ->left_join('cms_post', ['a.post_id', '=', 'p.post_id'], 'p')
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


}
