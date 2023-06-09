<?php

namespace Asfop\Tests\Constants;

use Asfop\Constants\Constant;

class ExampleConstant extends Constant
{
    /**
     * @message("登录")
     */
    const SIGN_IN = 0;
    /**
     * @message("注册")
     */
    const REGISTER = 1;
    /**
     * @message("找回密码")
     */
    const RETRIEVE_PASSWORD = 2;
    /**
     * @message("注销账号")
     * @color("danger")
     */
    const CANCEL_ACCOUNT = 3;

    /**
     * 重新从注解里面拿到的Message
     * @param mixed $description 描述
     * @param mixed $key key
     * @param mixed $value 值
     * @return mixed
     */
    protected static function message($description, $key, $value)
    {
        return $description;
    }

}
