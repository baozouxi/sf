<?php
namespace Home\Controller;


class DoctorController extends CommonController
{

    public function index()
    {
        $prefix = C('SESSION_PREFIX');
        $unique = $this->_unique('add_form');
        $this->assign(array(
            'add_form_unique' => $unique,
            'menu' => self::$menu,
            'hospital_name' => $_SESSION[$prefix]['hospital_name'],
        ));
        $this->display();
    }

    public function addDoctor()
    {
        if (!IS_AJAX || empty($_POST) || !isset($_POST['unique']) || $_POST['unique'] !== $_SESSION['add_form']) {
            $this->error('非法操作','Index/index',1);
            exit;
        }
        $errMsg = '';
        $prefix = C('SESSION_PREFIX');
        $doctorModel = M('doctor');
        $hospital_id = $_SESSION[$prefix]['hospital_id'];
        foreach ($_POST as $field => $v) {
            foreach ($v as $k => $item) {
                $errMsg = empty($item) ? '参数为空' : '';
                if ($field == 'age') {
                    $item = (int)$item;
                } else {
                    $item = addslashes(trim($item));
                }
                $res[$k][$field] = $item;
                $res[$k]['hospital_id'] = $hospital_id;
            }
        }

        if ($errMsg) {
            $this->ajaxReturn(array(
                'status' => 'error',
                'errMsg' => $errMsg
                ));
        } else {

            if($doctorModel->addAll($res)){
                $this->ajaxReturn(array(
                    'status' => 'ok',
                    ));
            }else{
                $this->ajaxReturn(array(
                    'status' => 'error',
                    'errMsg' => '添加失败，请稍后重试'
                    ));
            }

        }
    }


 
}