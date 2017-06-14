<?php

return array(
    '病人管理'  => array(
        '病人列表' => '/Home/Index/index',
        '添加病人' => '/Home/Patient/index',
		'添加医生' => '/Home/Doctor/index',
        '添加病种' => '/Home/Hospital/illness',
    ),
    '统计管理' => array(
        '按月统计' => '/Home/Spending/totalMonth',
        '按日统计' => '/Home/Spending/totalDay',
        '按年统计' => '/Home/Spending/totalYear',

    ),
    '消费表管理' => array(
        '生成下月消费' => '/Home/Spending/addNext',
        '生成当月消费' => '/Home/Spending/addCurrent',
    ),
    '医院信息' => array(
        // '医生列表' => '/Home/Doctor/list',
        '管理员列表' => '/Home/Admin/adminList',
        '添加管理员' => '/Home/Admin/index',
    ),
);