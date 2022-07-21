<?php

namespace Asfop\Tests;


use Asfop\Tests\Constants\ExampleConstant;
use PHPUnit\Framework\TestCase;

/**
 * ./vendor/bin/phpcbf --standard=PSR2 --colors src/
 */
class ExampleConstantTest extends TestCase
{
    // 获取常量的值
    public function testGet()
    {
        $this->assertEquals(0, ExampleConstant::SIGN_IN);
    }

    //获取所有已定义常量的名称
    public function testGetKeys()
    {
        $this->assertEquals([
            "SIGN_IN",
            "REGISTER",
            "RETRIEVE_PASSWORD",
            "CANCEL_ACCOUNT"
        ], ExampleConstant::getKeys());
    }

    // 根据常量的值获取常量的名称
    public function testGetKey()
    {
        $this->assertEquals('SIGN_IN', ExampleConstant::getKey(0));
    }

    // 获取所有已定义常量的值
    public function testGetValues()
    {
        $this->assertEquals([0, 1, 2, 3], ExampleConstant::getValues());
    }

    // 根据常量的名称获取常量的值
    public function testGetValue()
    {
        $this->assertEquals(2, ExampleConstant::getValue('RETRIEVE_PASSWORD'));
    }

    // 检查定义的常量中是否包含某个「常量值」
    public function testHasValue()
    {
        $this->assertEquals(true, ExampleConstant::hasValue(0));
        $this->assertEquals(false, ExampleConstant::hasValue(-1));
    }

    // 检查定义的常量中是否包含某个「常量名称」
    public function testHasKey()
    {
        $this->assertEquals(true, ExampleConstant::hasKey('RETRIEVE_PASSWORD'));
        $this->assertEquals(false, ExampleConstant::hasKey('RETRIEVE_PASSWORD_ADMIN'));
    }

    // 获取常量描述
    public function testDescription()
    {
        $this->assertEquals('注册', ExampleConstant::getDescription(ExampleConstant::REGISTER));
        $this->assertEquals('注册', ExampleConstant::REGISTER()->description);
        $exampleConstant = new ExampleConstant(ExampleConstant::REGISTER);
        $this->assertEquals('注册', $exampleConstant->description);
    }

    public function testToArray()
    {
        $this->assertEquals([
            'SIGN_IN' => 0,
            'REGISTER' => 1,
            'RETRIEVE_PASSWORD' => 2,
            'CANCEL_ACCOUNT' => 3,
        ], ExampleConstant::toArray());
    }

    public function testToSelectArray()
    {
        $this->assertEquals([
            0 => '登录',
            1 => '注册',
            2 => '找回密码',
            3 => '注销账号',
        ], ExampleConstant::toSelectArray());
    }
}