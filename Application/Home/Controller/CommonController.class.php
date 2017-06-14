<?php
namespace Home\Controller;

use Think\Controller;
use Home\Controller\MenuController;

class CommonController extends Controller
{

    protected static $menu = array();

    public function __construct()
    {
        parent::__construct();
        $prefix = C('SESSION_PREFIX');
        if (!isset($_SESSION[$prefix]['username']) || 
            !isset($_SESSION[$prefix]['uid']) || 
            !isset($_SESSION[$prefix]['hospital_id'])) {
            header('location:/Home/Login/index');
            exit;
        }
        // $file = COMMON_PATH.'/Common/menu.php';
        self::$menu = MenuController::getMenu($_SESSION[$prefix]['aprt_id']);
    }

        //表单唯一标识
    protected function _unique($name)
    {
        $code = mt_rand(0,9);
        for ($i=1;$i<5;$i++) {
            $code .= mt_rand(0,9);
        }
        return $_SESSION[$name] = sha1($code);
    }
    
}