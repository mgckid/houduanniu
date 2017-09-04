<?php

namespace app\controller;

use app\logic\BaseLogic;
use app\model\SiteConfigModel;
use houduanniu\web\Form;

/**
 * 系统设置控制器
 * @privilege 系统设置|Admin/System|dfd42e2a-5661-11e7-8c47-14dda97b937d|1
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/6/21
 * Time: 17:12
 */
class SystemController extends UserBaseController
{
    /**
     * 添加配置变量
     * @privilege 添加配置变量|Admin/System/addConfig|317a590a-5664-11e7-8c47-14dda97b937d|3
     */
    public function addConfig()
    {
        if (IS_POST) {
            $dictionaryLogic = new BaseLogic();
            $request_data = $dictionaryLogic->getRequestData('site_config','table');
            $model = new SiteConfigModel();
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail($this->getMessage());
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $id = isset($_GET['id'])?intval($_GET['id']):0;
            $siteConfigModel = new SiteConfigModel();
            $result = [];
            if ($id) {
                $result = $siteConfigModel->getRecordInfoById($id);
            }
            $dictionaryLogic = new BaseLogic();
            $form_init = $dictionaryLogic->getFormInit('site_config','table');
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '系统设置' => U('System/index'),
                '添加配置变量' => ''
            ));
            $this->display('System/addConfig');
        }
    }

    /**
     * 系统配置
     * @privilege 系统配置|Admin/System/sysConfig|3d22cfea-5673-11e7-8c47-14dda97b937d|2
     */
    public function sysConfig()
    {
        if (IS_POST) {
            $siteConfigModel = new SiteConfigModel();
            foreach ($_POST as $key => $value) {
                $condition=array(
                    'where'=>['name',$key],
                );
                $siteConfigModel->updateConfig($condition,['value'=>$value]);
            }
            $this->ajaxSuccess();
        } else {
            $siteConfigModel = new SiteConfigModel();
            $result = $siteConfigModel->getConfigList();
            $data = [
                'configList' => $result,
            ];

            #面包屑导航
            $this->crumb(array(
                '系统设置' => U('System/index'),
                '系统配置' => ''
            ));
            $this->display('System/sysConfig', $data);
        }
    }

    /**
     * 删除配置
     * @privilege 删除配置|Admin/System/delConfig|627054fe-56ee-11e7-9ea6-14dda97b937d|3
     */
    public function delConfig()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $model = new SiteConfigModel();
        #验证
        $rule = array(
            'id' => 'required',
        );
        $attr = array(
            'id' => '配置id',
        );
        $validate = $model->validate()->make($_POST, $rule, [],$attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (!$model->deleteRecordById($id)) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess($this->getMessage());
        }
    }
}