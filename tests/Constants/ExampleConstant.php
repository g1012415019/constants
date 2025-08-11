<?php

namespace Asfop\Tests\Constants;

use Asfop\Constants\Constant;

/**
 * @name("app订购模式")
 * @targetTable("yjy_app_order")
 * @targetTableColumn("buy_type")
 */
class ExampleConstant extends Constant
{
    /**
     * @message("Custom specification purchase")
     * @color("primary")
     */
    const CUSTOM = 1;
    /**
     * @message("Purchase per event")
     * @color("success")
     */
    const ONCE = 2;
    /**
     * @message("名称")
     * @color("#3AA1DA")
     */
    const TOW = 2;

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
