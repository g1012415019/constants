<?php

namespace Asfop\Tests\Constants;

use Asfop\Constants\Constant;

class ExampleConstant extends Constant
{
    /**
     * @translateMessage("登录")
     */
    const SIGN_IN = 0;
    /**
     * @message("注册")
     */
    const REGISTER = 1;
    /**
     * @translateMessage("找回密码")
     */
    const RETRIEVE_PASSWORD = 2;
    /**
     * @translateMessage("注销账号")
     */
    const CANCEL_ACCOUNT = 3;

}
