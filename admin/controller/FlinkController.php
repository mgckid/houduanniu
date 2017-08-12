<?php


namespace app\controller;

use app\model\FlinkModel;
use houduanniu\web\Form;
use houduanniu\base\Page;
use app\logic\DictionarryLogic;

/**
 * 友情链接管理控制器
 * @privilege 友情链接管理|Admin/Flink|c1a2f7e9-200a-11e7-8ad5-9cb3ab404081|1
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2016/7/29
 * Time: 14:12
 */
class FlinkController extends UserBaseController
{

    /**
     * 添加友情链接
     * @privilege 添加友情链接|Admin/Flink/addFlink|c1ac6529-200a-11e7-8ad5-9cb3ab404081|3
     */
    public function addFlink()
    {
        if (IS_POST) {
            $logic = new DictionarryLogic();
            $request_data = $logic->getRequestData('site_link', 'table');

            $FlinkModel = new FlinkModel();
            $result = $FlinkModel->addFlink($request_data);
            if ($result) {
                $this->ajaxSuccess('友情链接添加成功');
            } else {
                $this->ajaxFail('友情链接添加失败');
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            $logic = new DictionarryLogic();
            $form_init = $logic->getFormInit('site_link', 'table');

            $result = [];
            if ($id) {
                $FlinkModel = new FlinkModel();
                $result = $FlinkModel->getFlinkByID($id);
            }

            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '运营管理' => U('Flink/index'),
                '添加友情链接' => ''
            ));
            $this->display('Flink/addFlink');
        }
    }

    /**
     * 友情链接列表
     * @privilege 友情链接列表|Admin/Flink/flinkList|c217dd89-200a-11e7-8ad5-9cb3ab404081|2
     */
    public function flinkList()
    {

        $p = isset($_GET['p']) ? intval($_GET['p']) : 1;
        $pageSize = 20;
        #获取列表字段
        $dictionarylogic = new DictionarryLogic();
        $list_init = $dictionarylogic->getListInit('site_link');
        $flinkModel = new FlinkModel();
        $count = $flinkModel->getRecordList('', '', '', true);
        $page = new Page($count, $p, $pageSize, false);
        $result = $flinkModel->getRecordList('', $page->getOffset(), $pageSize, false);
        $data = array(
            'list' => $result,
            'page' => $page->getPageStruct(),
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '运营管理' => U('Flink/index'),
            '友情链接管理' => ''
        ));
        $this->display('Flink/flinkList', $data);
    }

    /**
     * 删除友情链接
     * @privilege 删除友情链接|Admin/Flink/delFlink|c2235b2e-200a-11e7-8ad5-9cb3ab404081|3
     */
    public function delFlink()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法访问');
        }
        $model = new FlinkModel();
        #验证
        $rule = array(
            'id' => 'required',
        );
        $attr = array(
            'id' => '友情链接ID',
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $result = $model->deleteRecordById($id);
        if (!$result) {
            $this->ajaxFail('删除失败');
        } else {
            $this->ajaxSuccess('删除成功');
        }
    }


}
