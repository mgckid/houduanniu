<?php


namespace app\controller;

use app\model\BaseModel;
use app\model\CmsAttributeModel;
use app\model\CmsFieldModel;
use app\model\CmsModelModel;
use app\model\DictionaryModel;
use app\logic\BaseLogic;
use houduanniu\web\Form;
use houduanniu\base\Page;

/**
 * 内容模型控制器
 * @privilege 内容模型|Admin/CmsModel|bd1327d8-6bc1-11e7-ab90-e03f49a02407|1
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 22:01
 */
class CmsModelController extends UserBaseController
{

    /**
     * 模型管理
     * @privilege 模型管理|Admin/CmsModel/modelManage|8a7c80a9-6c2c-11e7-ba80-5996e3b2d0fb|2
     */
    public function modelManage()
    {
        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit('cms_model');
        #查询列表
        {
            $cmsModelModel = new CmsModelModel();
            $result = $cmsModelModel->orm()->find_array();
        }
        $data = array(
            'list' => $result,
            'list_init' => $list_init
        );
        #面包屑导航
        $this->crumb(array(
            '内容模型' => U('Cms/index'),
            '模型管理' => ''
        ));
        $this->display('CmsModel/modelManage', $data);

    }

    /**
     * 字段管理
     * @privilege 字段管理|Admin/CmsModel/fieldManage|986d8a01-6c2c-11e7-ba80-5996e3b2d0fb|3
     */
    public function fieldManage()
    {
        $model_id = isset($_GET['model_id']) ? intval($_GET['model_id']) : 0;
        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit('cms_field');
        #查询列表
        {
            $cmsFieldModel = new CmsFieldModel();
            $result = $cmsFieldModel->getRecordList($cmsFieldModel->orm()->where('model_id', $model_id), '', 999, false, false);
            if ($result) {
                $cmsAttributeModel = new CmsAttributeModel();
                foreach ($result as $key => $value) {
                    $value['attr_count'] = $cmsAttributeModel->getRecordList($cmsAttributeModel->orm()->where('field_id', $value['id']), '', '', true);
                    $result[$key] = $value;
                }
            }
        }
        $data = array(
            'list' => $result,
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '模型管理' => U('CmsModel/modelManage'),
            '字段管理' => '',
        ));
        $this->display('CmsModel/fieldManage', $data);
    }

    /**
     * 字段管理
     * @privilege 字段管理|Admin/CmsModel/attributeManage|06173ad3-72a4-11e7-ba80-5996e3b2d0fb|3
     */
    public function attributeManage()
    {
        $field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;
        $dictionaryLogic = new BaseLogic();
        $list_init = $dictionaryLogic->getListInit('cms_attribute');
        #查询列表
        {
            $cmsAttributeModel = new CmsAttributeModel();
            $result = $cmsAttributeModel->getRecordList($cmsAttributeModel->orm()->where('field_id', $field_id), '', 999, false, false);
        }
        $data = array(
            'list' => $result,
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '模型管理' => U('CmsModel/modelManage'),
            '属性管理' => '',
        ));
        $this->display('CmsModel/attributeManage', $data);
    }

    /**
     * 添加内容模型
     * @privilege 添加内容模型|Admin/CmsModel/addModel|e8d680dd-6c2c-11e7-ba80-5996e3b2d0fb|3
     */
    public function addModel()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('cms_model', 'table');

            $cmsModelModel = new CmsModelModel();
            $result = $cmsModelModel->addCmsModel($request_data);
            if ($result) {
                $this->ajaxSuccess();
            } else {
                $this->ajaxFail();
            }
        } else {
            $cmsModelModel = new CmsModelModel();
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $result = $cmsModelModel->getCmsModelInfo($cmsModelModel->orm()->where('id', $id));

            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('cms_model', 'table');

            Form::getInstance()->form_schema($form_init)->form_data($result);
            $this->display('CmsModel/addModel');
        }
    }


    /**
     * 添加模型字段
     * @privilege 添加模型字段|Admin/CmsModel/addField|62e7961a-7290-11e7-ba80-5996e3b2d0fb|3
     */
    public function addField()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('cms_field', 'table');

            $cmsFieldModel = new CmsFieldModel();
            $result = $cmsFieldModel->addRecord($request_data);
            if ($result) {
                $this->ajaxSuccess();
            } else {
                $this->ajaxFail();
            }
        } else {
            $cmsModelModel = new CmsModelModel();
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $model_id = isset($_GET['model_id']) ? intval($_GET['model_id']) : 0;
            $result = [];
            if ($id) {
                $result = $cmsModelModel->orm()->for_table('cms_field')->where('id', $id)->find_one()->as_array();
            }
            if (!$result && $model_id) {
                $result['model_id'] = $model_id;
            }

            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('cms_field', 'table');
            #补充枚举数据
            {
                $model_result = $cmsModelModel->orm()->select_expr('name,id')->find_array();
                $enum = [];
                foreach ($model_result as $value) {
                    $enum[] = [
                        'value' => $value['id'],
                        'option' => $value['name'],
                    ];
                }
                $form_init['model_id']['enum'] = $enum;
            }
            Form::getInstance()->form_schema($form_init)->form_data($result);
            $this->display('CmsModel/addField');
        }
    }

    /**
     * 添加模型字段字段属性
     * @privilege 添加模型字段属性|Admin/CmsModel/addAttribute|ee75adda-772a-11e7-ba80-5996e3b2d0fb|3
     */
    public function addAttribute()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('cms_attribute', 'table');

            $cmsAttributeModel = new CmsAttributeModel();
            $result = $cmsAttributeModel->addRecord($request_data);
            if ($result) {
                $this->ajaxSuccess();
            } else {
                $this->ajaxFail();
            }
        } else {
            $cmsAttributeModel = new CmsAttributeModel();
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;
            $result = [];
            if ($id) {
                $result = $cmsAttributeModel->getRecordInfoById($id);
            }
            if (!$result && $field_id) {
                $result['field_id'] = $field_id;
            }
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('cms_attribute', 'table');

            Form::getInstance()->form_schema($form_init)->form_data($result);
            $this->display('CmsModel/addAttribute');
        }
    }

    /**
     * 删除记录
     * @privilege 删除记录|Admin/CmsModel/delRecord|b53ee342-774d-11e7-ba80-5996e3b2d0fb|3
     */
    public function delRecord()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $model = new BaseModel();
        #验证
        $rule = array(
            'id' => 'required|integer',
            'type' => 'in:model,field,attribute'
        );
        $attr = array(
            'id' => '配置id',
            'type' => '类型'
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        if ($type == 'model') {
            $model == new CmsModelModel();
        } elseif ($type == 'field') {
            $model = new CmsFieldModel();
        } elseif ($type == 'attribute') {
            $model = new CmsAttributeModel();
        }
        #删除记录
        if (!$model->deleteRecordById($id)) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess($this->getMessage());
        }
    }


}