<?php
namespace  Home\Controller;


class HospitalController extends CommonController
{

    public function illness()
    {
        $this->assign(array(
            'menu' => self::$menu,
            'add_form_unique' => $this->_unique('add_form'),
            'hospital_name' => $_SESSION[C('SESSION_PREFIX')]['hospital_name'],
            ));
        $this->display();
    }

    public function addIllness()
    {
        if (!IS_AJAX || !IS_POST 
            || !filter_has_var(INPUT_POST, 'name')
            || !filter_has_var(INPUT_POST, 'unique')
            || $_POST['unique'] !== $_SESSION['add_form']
            ) {
            $this->error('非法操作');
            exit;
        }  
        $_POST['illness_name'] =  htmlspecialchars(addslashes(trim($_POST['name'])));
        $_POST['hospital_id']  = $_SESSION[C('SESSION_PREFIX')]['hospital_id'];
        $model = M('illness');
        if ($model->data($_POST)->add()) {
            $this->ajaxReturn(array(
                'status' => 'ok'
                ));
        } else {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => '添加失败'
                ));
        }
    }
}
