<h1 align="center"> constants </h1>

> A simple and easy-to-use enumeration extension package to help you manage enumerations in your project more
> conveniently
> - 一个简单好用的枚举扩展包，帮助你更方便地管理项目中的枚举

## 本扩展包在 [jiannei/laravel-enum](https://github.com/jiannei/laravel-enum) 修改而来

## 介绍

`asfop/constants` 主要用来扩展项目中的常量使用，通过合理的定义常量可以使代码更加规范，更易阅读和维护。

当您需要定义错误码和错误信息时，可能会使用以下方式，

```php
<?php

class ErrorCode
{
    const SERVER_ERROR = 500;
    const PARAMS_INVALID = 1000;

    public static $messages = [
        self::SERVER_ERROR => 'Server Error',
        self::PARAMS_INVALID => '参数非法'
    ];
}

$message = ErrorCode::messages[ErrorCode::SERVER_ERROR] ?? '未知错误';

```

但这种实现方式并不友好，每当要查询错误码与对应错误信息时，都要在当前 Class 中搜索两次，

## 概览

- 提供了多种实用的方式来实例化枚举
- 支持基于注解多语言本地化描述

## 安装

```shell
$ composer require "asfop/constants"
```

## 使用

更为具体的使用可以查看测试用例：[https://github.com/g1012415019/constants/blob/main/tests](https://github.com/g1012415019/constants/tree/main/tests)

### 常规使用

- 定义

```php
<?php

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
     * @messageEn("Account cancellation")
     * @messageZhTW("註銷賬號")
     */
    const CANCEL_ACCOUNT = 3;

//    /**
//     * 重写从注解里面拿到的Message
//     * @param mixed $description 描述
//     * @param mixed $key key
//     * @param mixed $value 值
//     * @return mixed
//     */
//    protected static function message($description, $key, $value)
//    {
//        return $description;
//    }

}
```

- 使用

```php
// 获取常量的值
ExampleConstant::SIGN_IN;// 0

// 获取所有已定义常量的名称
$keys = ExampleConstant::getKeys();// ['SIGN_IN ', 'REGISTER', 'RETRIEVE_PASSWORD ', 'CANCEL_ACCOUNT']

// 根据常量的值获取常量的名称
ExampleConstant::getKey(1);// REGISTER 

// 获取所有已定义常量的值
$values = ExampleConstant::getValues();// [0, 1, 2, 3]

// 根据常量的名称获取常量的值
ExampleConstant::getValue('REGISTER ');// 1
```

- 本地化描述

```php

// 返回中文描述
ExampleConstant::getDescription(ExampleConstant::CANCEL_ACCOUNT);// 注销账号

// 返回指定语言描述，参数二为语言文化代码，在枚举注解中无需“-”
ExampleConstant::getDescriptionI18n(ExampleConstant::CANCEL_ACCOUNT, 'en');// Account cancellation

// 补充：也可以先实例化常量对象，然后再根据对象实例来获取常量描述
$responseEnum = new ExampleEnum(ExampleEnum::CANCEL_ACCOUNT);
$responseEnum->description;// 注销账号

// 其他方式
ExampleConstant::CANCEL_ACCOUNT()->description;// 注销账号

```

- 枚举校验

```php
// 检查定义的常量中是否包含某个「常量值」
ExampleConstant::hasValue(1);// true
ExampleConstant::hasValue(-1);// false

// 检查定义的常量中是否包含某个「常量名称」 

ExampleConstant::hasKey('CANCEL_ACCOUNT ');// true
ExampleConstant::hasKey('ACCOUNT ');// false
```

- 枚举实例化：枚举实例化以后可以方便地通过对象实例访问枚举的 key、value 以及 description 属性的值。

```php
// 方式一：new 传入常量的值
$administrator1 = new ExampleConstant(ExampleConstant::CANCEL_ACCOUNT);

// 方式二：fromValue
$administrator2 = ExampleConstant::fromValue(0);

Asfop\Tests\Constants\ExampleConstant Object
(
    [description] => 登录
    [key] => SIGN_IN
    [value] => 0
)

// 方式三：fromKey
$administrator3 = ExampleConstant::fromKey('CANCEL_ACCOUNT');

Asfop\Tests\Constants\ExampleConstant Object
(
    [description] => 注销账号
    [key] => CANCEL_ACCOUNT
    [value] => 3
)

// 方式四：magic
$administrator4 = ExampleConstant::CANCEL_ACCOUNT();

Asfop\Tests\Constants\ExampleConstant Object
(
    [description] => 注销账号
    [key] => CANCEL_ACCOUNT
    [value] => 3
)

```

- toArray

```php
$array = ExampleConstant::toArray();

/*
[
    'SIGN_IN' => 0,
    'REGISTER' => 1,
    'RETRIEVE_PASSWORD' => 2,
    'CANCEL_ACCOUNT' => 3,
]
*/
```

- toSelectArray

```php
$array = ExampleConstant::toSelectArray();

/*
[
  0 => '登录',
  1 => '注册',
  2 => '找回密码',
  3 => '注销账号',
]
*/

```

- 获取枚举注解

```php

// 枚举可以存在多个注解，枚举定义：
/**
 * @message("注销账号")
 * @color("danger")
 * ...
 */
const CANCEL_ACCOUNT = 0;

// 使用，获取指定枚举单个注解值
ExampleConstant::getAnnotationOne(ExampleConstant::CANCEL_ACCOUNT, 'color');// danger
// 获取指定枚举所有注解值
ExampleConstant::getAnnotationList(ExampleConstant::CANCEL_ACCOUNT);

/*
[
  {
    "function": "message",
    "value": "注销账号"
  },
  {
    "function": "color",
    "value": "danger"
  }
]
*/

```

## 参考

- [jiannei/laravel-enum](https://github.com/jiannei/laravel-enum)
- [hyperf/constants](https://github.com/hyperf/constants)
- [PHP 使用反射获取常量名、值及注释](https://blog.csdn.net/nbaqq2010/article/details/124197478)

## License

MIT