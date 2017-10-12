<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/8/1
 * Time: 11:00
 */

namespace houduanniu\web;

use houduanniu\base\Application;


class Form
{
    static protected $instance;
    #表单结构数据
    protected $form_schema;
    #表单数据
    protected $form_data;
    #表单提交地址
    protected $form_action;
    #表单提交方法
    protected $form_method;
    #表单样式
    protected $form_class;
    #表单名称
    protected $form_name;


    protected function __construct($form_name = '', $form_method = '', $form_action = '', $form_class = '')
    {
        $this->form_name = !empty($form_name) ? $form_name : 'autoform';
        $this->form_method = !empty($form_method) ? $form_method : 'post';
        $this->form_action = !empty($form_action) ? $form_action : $_SERVER['REQUEST_URI'];
        $this->form_class = !empty($form_class) ? $form_class : 'form form-horizontal';
    }

    /**
     * 获取类实例化对象
     * @return $this
     */
    public static function getInstance($form_name = '', $form_method = '', $form_action = '', $form_class = '')
    {
        if (empty(self::$instance)) {
            self::$instance = new self($form_name, $form_method, $form_action, $form_class);
        }
        return self::$instance;
    }

    /**
     * 添加表单结构数据
     * @access protected
     * @author furong
     * @param $form_schema
     * @return $this
     * @since
     * @abstract
     */
    public function form_schema($form_schema)
    {
        $this->form_schema = $form_schema;
        return $this;
    }

    /**
     * 添加表单数据
     * @access public
     * @author furong
     * @param $form_data
     * @return $this
     * @since
     * @abstract
     */
    public function form_data($form_data)
    {
        $this->form_data = $form_data;
        return $this;
    }

    /**
     * 设置表单提交地址
     * @access public
     * @author furong
     * @param $action
     * @return $this
     * @since
     * @abstract
     */
    public function form_action($action)
    {
        $this->form_action = $action;
        return $this;
    }

    /**
     * 设置表单name
     * @access public
     * @author furong
     * @param $name
     * @return $this
     * @since
     * @abstract
     */
    public function form_name($name)
    {
        $this->form_name = $name;
        return $this;
    }

    /**
     * 设置表单form class
     * @access public
     * @author furong
     * @param $class
     * @return $this
     * @since
     * @abstract
     */
    public function form_class($class)
    {
        $this->form_class = $class;
        return $this;
    }

    /**
     * 添加post提交
     * @access public
     * @author furong
     * @return $this
     * @since
     * @abstract
     */
    public function form_method_post()
    {
        self::getInstance()->form_method = 'post';
        return $this;
    }

    /**
     * 添加get提交
     * @access public
     * @author furong
     * @return $this
     * @since
     * @abstract
     */
    public function form_method_get()
    {
        self::getInstance()->form_method = 'get';
        return $this;
    }

    /**
     * 添加文本域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_text($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'text'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加密码域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_password($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'password'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加隐藏域元素
     * @access public
     * @author furong
     * @param $name  input name
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_hidden($name, $default_value = '')
    {
        $schema = [
            'name' => $name,
            'type' => 'hidden'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加文件域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function input_file($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'file'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加单选域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function radio($title, $name, $enum, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'radio',
            'enum' => $enum,
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加多选域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function checkbox($title, $name, $enum, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'checkbox',
            'enum' => $enum,
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加下拉域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function select($title, $name, $enum, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'select',
            'enum' => $enum,
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加多行文本域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function textarea($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'textarea'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }

    /**
     * 添加富文本编辑器域元素
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name  input name
     * @param string 描述
     * @param string 默认值
     * @return $this
     * @since
     * @abstract
     */
    public function editor($title, $name, $description = '', $default_value = '')
    {
        $schema = [
            'title' => $title,
            'name' => $name,
            'description' => $description,
            'type' => 'textarea'
        ];
        $this->form_schema[] = $schema;
        $this->form_data[$name] = $default_value;
        return $this;
    }


    /**
     * 创建表单
     * @access public
     * @author furong
     * @return string
     * @since
     * @abstract
     */
    public  function  create()
    {
        $form_action_str = !empty(self::getInstance()->form_action) ? 'action="' . self::getInstance()->form_action . '"' : '';
        $form_name_str = !empty(self::getInstance()->form_name) ? 'name="' . self::getInstance()->form_name . '"' : '';
        $form_id_str = !empty(self::getInstance()->form_name) ? 'id="' . self::getInstance()->form_name . '"' : '';
        $form_method_str = !empty(self::getInstance()->form_method) ? 'method="' . self::getInstance()->form_method . '"' : '';
        $form_class_str = !empty(self::getInstance()->form_class) ? 'class="' . self::getInstance()->form_class . '"' : '';
        $inputs_str = !empty(self::getInstance()->form_schema) ? self::getInstance()->render(self::getInstance()->form_schema, self::getInstance()->form_data) : '';
        $template = '<form %s %s %s %s %s> %s';
        return sprintf($template, $form_action_str, $form_name_str, $form_id_str, $form_method_str, $form_class_str, $inputs_str);
    }

    /**
     * 创建表单input
     * @access public
     * @author furong
     * @param $form_schema
     * @param array $form_data
     * @return string
     * @since
     * @abstract
     */
    public function render($form_schema, $form_data = [])
    {
        $form_str = '';
        foreach ($form_schema as $value) {
            $title = $value['title'];
            $description = isset($value['description']) ? $value['description'] : '';
            /*input属性开始*/
            $type = $value['type'];
            $name = $value['name'];
            $placeholder = '请输入' . $title;
            $enum = isset($value['enum']) ? $value['enum'] : [];
            $default_value = isset($form_data[$name]) ? $form_data[$name] : '';
            /*input属性结束*/

            $form_group_str = '';
            switch ($type) {
                case 'text':
                    $form_group_str = $this->render_input_text($title, $name, $placeholder, $description, $default_value);
                    break;
                case 'hidden':
                    $form_group_str = $this->render_input_hidden($name, $default_value);
                    break;
                case 'password':
                    $form_group_str = $this->render_input_password($title, $name, $placeholder, $description, $default_value);
                    break;
                case "radio":
                    $form_group_str = $this->render_radio($title, $name, $enum, $description, $default_value);
                    break;
                case 'checkboxs':
                    $form_group_str = $this->render_checkbox($title, $name, $enum, $description, $default_value);
                    break;
                case 'select':
                    $form_group_str = $this->render_select($title, $name, $enum, $description, $default_value);
                    break;
                case 'textarea':
                    $form_group_str = $this->render_textarea($title, $name, $placeholder, $description, $default_value);
                    break;
                case 'editor':
                    $form_group_str = $this->render_editor($title, $name, $placeholder, $description, $default_value);
                    break;
                case 'file':
                    $form_group_str = $this->render_input_file($title, $name, $description, $default_value);
                    break;
            }
            $form_str .= $form_group_str;
        }
        return $form_str;
    }


    /**
     * 渲染文本域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_text($title, $name, $placholder, $description, $value = '')
    {
        $input_str = $this->render_input('text', $name, 'form-control', $placholder, $value);
        $html = $this->buildFormGroup($title, $input_str, $description);
        return $html;
    }

    /**
     * 渲染隐藏域结构
     * @access public
     * @author furong
     * @param $name input name
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_hidden($name, $value = '')
    {
        $html = $this->render_input('hidden', $name, '', '', $value);
        return $html;
    }

    /**
     * 渲染密码域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_password($title, $name, $placholder, $description, $value = '')
    {
        $input_str = $this->render_input('password', $name, 'form-control', $placholder, $value);
        $html = $this->buildFormGroup($title, $input_str, $description);
        return $html;
    }

    /**
     * 渲染单选域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $enum 选项数据
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_radio($title, $name, $enum, $description, $default_value = '')
    {
        $radis_str = '';
        $type_str = 'type="radio"';
        $name_str = !empty($name) ? 'name="' . $name . '"' : '';
        $id_str = !empty($name) ? 'id="' . $name . '"' : '';
        foreach ($enum as $key => $value) {
            $checked_str = $value['value'] == $default_value ? 'checked="true"' : '';
            $value_str = !empty($value['value']) ? 'value="' . $value['value'] . '"' : '';
            $radio_text_str = $value['name'];
            $template = '<input %s %s %s %s %s /> %s';
            $radis_str .= sprintf($template, $type_str, $name_str, $id_str, $value_str, $checked_str, $radio_text_str);
        }
        $html = $this->buildFormGroup($title, $radis_str, $description);
        return $html;
    }

    /**
     * 渲染多选域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $enum 选项数据
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_checkbox($title, $name, $enum, $description, $default_value = '')
    {
        $form_control_str = '';
        foreach ($enum as $key => $value) {
            $input_str = $this->render_input('checkbox', $name, '', '', $value) . ' ' . $value;
            $form_control_str . sprintf('<label class="checkbox-inline">%s</label>', $input_str);
        }
        $html = $this->buildFormGroup($title, $form_control_str, $description);
        return $html;
    }

    /**
     * 渲染多行文本域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_textarea($title, $name, $placeholder, $description, $default_value = '')
    {
        $name_str = !empty($name) ? 'name="' . $name . '"' : '';
        $id_str = !empty($name) ? 'id="' . $name . '"' : '';
        $class_str = 'class="form-control"';
        $placeholder_str = !empty($placeholder) ? 'placeholder="' . $placeholder . '"' : '';
        $value_str = $default_value;
        $template = '<textarea %s %s %s %s >%s</textarea>';

        $html = sprintf($template, $name_str, $id_str, $class_str, $placeholder_str, $value_str);
        $html = $this->buildFormGroup($title, $html, $description);
        return $html;
    }

    /**
     * 渲染富文本编辑器域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $placholder 输入提示
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_editor($title, $name, $placeholder, $description, $default_value = '')
    {
        $name_str = !empty($name) ? 'name="' . $name . '"' : '';
        $id_str = !empty($name) ? 'id="' . $name . '"' : '';
        $placeholder_str = !empty($placeholder) ? 'placeholder="' . $placeholder . '"' : '';
        $value_str = !empty($default_value) ? $default_value : '';
        $template = '<textarea %s %s %s style="height:500px;" >%s</textarea>';
        $html = sprintf($template, $name_str, $id_str, $placeholder_str, $value_str);
        $html = $this->buildFormGroup($title, $html, $description);
        return $html;
    }

    /**
     * 渲染文件域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_input_file($title, $name, $description, $default_value = '')
    {
        $hiddenInput = $this->render_input('hidden', $name, '', '', $default_value);
        $image_url = !empty($default_value) ? getImage($default_value) : '';
        $fileInput = '<input type="file" id="upload_file" data-preview="' . $image_url . '" />';

        $html = $hiddenInput . $fileInput;
        $html = $this->buildFormGroup($title, $html, $description);
        return $html;
    }

    /**
     * 渲染下拉选择域结构
     * @access public
     * @author furong
     * @param $title 标题
     * @param $name input name
     * @param $enum 选项数据
     * @param $description 描述
     * @param string $value 默认值
     * @return string
     * @since
     * @abstract
     */
    protected function render_select($title, $input_name, $enum, $description, $select_value)
    {
        $name_str = !empty($input_name) ? 'name="' . $input_name . '"' : '';
        $id_str = !empty($input_name) ? 'id="' . $input_name . '"' : '';
        $class_str = 'class="form-control"';
        $selected_data_str = !empty($select_value) ? 'data-selected="' . $select_value . '"' : '';
        $options_str = '<option value="">请选择</option>';
        foreach ($enum as $key => $value) {
            $value_str = !empty($value['value']) ? 'value="' . $value['value'] . '"' : '';
            $selected_str = ($select_value == $value['value']) ? 'selected="true"' : '';

            $option = !empty($value['option']) ? $value['option'] : '';

            $option_template = '<option %s %s >%s</option>';
            $options_str .= sprintf($option_template, $value_str, $selected_str, $option);
        }
        $select_template = '<select %s %s %s %s>%s</select>';
        $html = sprintf($select_template, $name_str, $id_str, $class_str, $selected_data_str, $options_str);
        $html = $this->buildFormGroup($title, $html, $description);
        return $html;
    }

    /**
     * 构建input表单
     * @access protected
     * @author furong
     * @param $type 表单类型
     * @param $name 表单名称
     * @param $fieldHtmlClass 字段样式class
     * @param $placeholder 默认提示
     * @return string
     * @since  2017年7月13日 16:22:14
     * @abstract
     */
    protected function render_input($type, $name, $input_class, $placeholder, $default_value = '')
    {
        $type_str = !empty($type) ? 'type="' . $type . '"' : '';
        $name_str = !empty($name) ? 'name="' . $name . '"' : '';
        $id_str = !empty($name) ? 'id="' . $name . '"' : '';
        $class_str = !empty($input_class) ? 'class="' . $input_class . '"' : '';
        $placeholder_str = !empty($placeholder) ? 'placeholder="' . $placeholder . '"' : '';
        $value_str = !empty($default_value) ? 'value="' . $default_value . '"' : '';

        $template = '<input %s %s %s %s %s %s />';

        return sprintf($template, $type_str, $name_str, $id_str, $class_str, $placeholder_str, $value_str);
    }

    /**
     * 构建表单组
     * @access  protected
     * @author furong
     * @param $htmlClass
     * @param $title_str
     * @param $form_control_str
     * @return string
     * @since 2017年7月13日 16:56:17
     * @abstract
     */
    protected function buildFormGroup($title, $input_str, $description, $form_group_class = "", $title_class = "", $input_class = "", $description_class = "")
    {
        $template = <<<EOT
             <div class="form-group">
               <label class="control-label col-sm-2"> %s</label>
                <div class="col-sm-8">
                  %s
                </div>
                <label class="col-sm-2"> %s</label>
              </div>
EOT;
        return sprintf($template, $title, $input_str, $description);
    }


}