<?php


namespace app\controller;

use app\logic\BaseLogic;
use app\logic\Post;
use app\model\BaseModel;
use app\model\CmsCategoryModel;
use app\model\CmsModelModel;
use app\model\CmsPostModel;
use app\model\CmsPageModel;
use app\model\CmsTagModel;
use app\model\CoreTextModel;
use app\model\DictionaryModelModel;
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
            $logic = new BaseLogic();
            $model = new CmsCategoryModel();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            #获取表单初始化数据
            $logic = new BaseLogic();
            $model = new CmsCategoryModel();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '添加栏目' => ''
            ));
            $this->display('Cms/addCategory');
        }
    }

    /**
     * 修改栏目
     * @privilege 修改栏目|Admin/Cms/editCategory|f7effdf6-776f-11e7-ba80-dsjhgds566|3
     */
    public function editCategory()
    {
        if (IS_POST) {
            $this->addCategory();
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $baseLogic = new BaseLogic();
            $cmsCategoryModel = new CmsCategoryModel();
            $result = $cmsCategoryModel->getRecordInfoById($id);
            #获取表单初始化数据
            $form_init = $baseLogic->getFormInit($cmsCategoryModel->getTableName(), 'table');
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '修改栏目' => ''
            ));
            $this->display('Cms/addCategory');
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
            $baseLogic = new BaseLogic();
            $model_result = $baseLogic->getModelInfo($model_id);
            if (!$model_result) {
                $this->ajaxSuccess('内容模型不存在');
            }
            $model_name = $model_result['dictionary_value'];
            $request_data = $baseLogic->getRequestData($model_name, 'model');
            $cmsPostModel = new CmsPostModel();
            $result = $cmsPostModel->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail('文档添加失败,' . $this->getMessage());
            } else {
                $this->ajaxSuccess('文档添加成功');
            }
        } else {
            $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
            $model_name = isset($_GET['model_name']) ? trim($_GET['model_name']) : '';

            if (!$category_id && !$model_name) {
                die('请选择栏目或者模型');
            }

            #获取栏目信息
            $category_result = [];
            $cmsCategoryModel = new CmsCategoryModel();
            if ($category_id) {
                $category_result = $cmsCategoryModel->getRecordInfoById($category_id);
            }

            #获取模型信息
            $baseLogic = new BaseLogic();
            $model_name = !empty($model_name) ? $model_name : $category_result['model_id'];
            $model_result = $baseLogic->getModelInfo($model_name);
            if (!$model_result) {
                die('内容模型不存在');
            }
            #获取表单初始化数据
            $form_init = $baseLogic->getFormInit($model_result['dictionary_value'], 'model');
            #添加文档是默认数据
            $form_data['category_id'] = $category_id;
            $form_data['model_id'] = $model_result['id'];
            $form_data['post_id'] = getItemId();
            Form::getInstance()->form_data($form_data)
                ->form_schema($form_init);

            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '添加文档' => ''
            ));
            $template = !empty($model_result['post_add_template'])?$model_result['post_add_template']:'Cms/addPost';
            $this->display($template);
        }
    }

    /**
     * 编辑文档
     * @privilege 编辑文档|Admin/Cms/editPost|e91d2442-2006-11e7-8ad5-9kjhkjhkjhgjhg|3
     */
    public function editPost()
    {
        if (IS_POST) {
            #获取模型信息
            $model_id = isset($_POST['model_id']) ? intval($_POST['model_id']) : 0;
            $baseLogic = new BaseLogic();
            $model_result = $baseLogic->getModelInfo($model_id);
            if (!$model_result) {
                $this->ajaxSuccess('内容模型不存在');
            }
            $model_name = $model_result['dictionary_value'];
            $request_data = $baseLogic->getRequestData($model_name, 'model');
            $cmsPostModel = new CmsPostModel();
            $result = $cmsPostModel->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail('文档添加失败,' . $this->getMessage());
            } else {
                $this->ajaxSuccess('文档添加成功');
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $baseLogic = new BaseLogic();
            $cmsPostModel = new CmsPostModel();
            #获取文档信息
            $post_result = $cmsPostModel->getRecordInfoById($id, '*');
            #获取表单初始化数据
            $form_init = $baseLogic->getFormInit($post_result['model_id'], 'model');
            Form::getInstance()->form_data($post_result)
                ->form_schema($form_init);
            #获取模型信息
            $model_result = $baseLogic->getModelInfo($post_result['model_id']);
            #面包屑导航
            $this->crumb(array(
                '内容管理' => U('Cms/index'),
                '编辑文档' => ''
            ));
            $template = !empty($model_result['post_add_template'])?$model_result['post_add_template']:'Cms/addPost';
            $this->display($template);
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
        $all_category_result = $cmsCategoryModel->getAllRecord($cmsCategoryModel->orm()->where('deleted', 0));
        $list = treeStructForLevel($all_category_result);
        #获取列表字段
        $dictionarylogic = new BaseLogic();
        $list_init = $dictionarylogic->getListInit('cms_category');
//        print_g($list_init);
        /*        #完善列表字段枚举值
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
                }*/
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

        #获取栏目信息
        {
            $cmsCategoryModel = new CmsCategoryModel();
            $category_result = $cmsCategoryModel->getRecordInfoById($category_id);
        }
        #获取列表数据
        {
            $model = new CmsPostModel();
            $orm = $model->orm()->where('category_id', $category_id);
            #统计记录数
            $count = $model->getRecordList($orm, '', '', TRUE);
            #分页
            $page = new Page($count, $p, $fetch_row);
            $list = $model->getRecordList($orm, $page->getOffset(), $fetch_row, FALSE);
        }
        #获取列表字段
        {
            $dictionaryLogic = new BaseLogic();
            $list_init = $dictionaryLogic->getListInit($model->getTableName());
        }
        #完善列表字段枚举值
        {
            #父级栏目
            $all_category_result = $cmsCategoryModel->getAllRecord();
            $enum = [];
            foreach ($all_category_result as $value) {
                $enum[$value['id']] = $value['category_name'];
            }
            $enum[0] = '根目录';
            $list_init['category_id']['enum'] = $enum;
            #栏目模型
            $enum = [];
            $cmsModelModel = new DictionaryModelModel();
            $model_result = $cmsModelModel->getAllRecord();
            foreach ($model_result as $value) {
                $enum[$value['id']] = $value['dictionary_name'];
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


}
