<?php

namespace Asfop\Constants;

use ReflectionClass;
use ReflectionClassConstant;

abstract class Constant
{

    protected static $cache = [];
    public $description;
    public $key;
    public $value;

    /**
     * 原样输出message
     * @var string
     */
    protected static $functionMessage = 'message';

    /**
     * @var string
     */
    protected static $functionColor = 'color';

    public function __construct($enumValue, bool $strict = true)
    {
        $this->description = static::getDescription($enumValue);
        $this->key = static::getKey($enumValue);
        $this->value = $enumValue;
    }

    /**
     * 检查定义的常量中是否包含某个「常量值」
     * @param $value
     * @param bool $strict
     * @return bool
     */
    public static function hasValue($value, bool $strict = true): bool
    {
        $validValues = static::getValues();

        if ($strict) {
            return in_array($value, $validValues, true);
        }

        return in_array((string)$value, array_map('strval', $validValues), true);
    }

    /**
     * 获取所有已定义常量的值
     * @return array
     */
    public static function getValues(): array
    {
        $constants = static::getConstants();
        $result = [];
        foreach ($constants as $index => $constant) {
            $result[$index] = $constant['value'];
        }
        return array_values($result);
    }

    //所有已定义常量的名称
    protected static function getConstants(): array
    {
        $calledClass = static::class;

        if (!array_key_exists($calledClass, static::$cache)) {
            $reflect = new ReflectionClass($calledClass);
            // 获取常量名
            $constants = $reflect->getConstants();

            // 获取常量名注释列表
            $result = [];
            foreach ($constants as $constant => $constantValue) {
                $result[$constant] = [
                    'annotation' => self::parse(self::getConstantDocComment($constant)) ?? [],
                    'key' => $constant,
                    'value' => $constantValue
                ];
            }
            static::$cache[$calledClass] = $result;
        }

        return static::$cache[$calledClass];
    }

    /**
     * 获取常量注释
     * @param string $key 常量名
     * @return string
     */
    private static function getConstantDocComment(string $key): string
    {
        return preg_replace('#[\*\s]*(^/|/$)[\*\s]*#', '', (new ReflectionClassConstant(static::class, $key))->getDocComment());
    }

    /**
     * 解析注解
     * @param string $doc
     * @param array $previous
     * @return array
     */
    protected static function parse(string $doc, array $previous = []): array
    {
        $pattern = '/\\@(\\w+)\\(\\"(.+)\\"\\)/U';
        if (preg_match_all($pattern, $doc, $result)) {
            if (isset($result[1], $result[2])) {
                $keys = $result[1];
                $values = $result[2];
                foreach ($keys as $i => $key) {
                    if (isset($values[$i])) {
                        $previous[] = [
                            'function' => self::camelize($key),
                            'value' => $values[$i],
                        ];
                    }
                }
            }
        }

        return $previous;
    }

    protected static function camelize($words, $separator = '_'): string
    {
        $words = $separator . str_replace($separator, " ", strtolower($words));
        return ltrim(str_replace(" ", "", ucwords($words)), $separator);
    }

    public static function getKey($value): ?string
    {
        return array_column(static::getConstants(), 'key', 'value')[$value] ?? null;
    }

    //1. 不存在语言包的情况，返回较为友好的英文描述
    public static function getDescription($value): ?string
    {
        return self::getLocalizedAnnotation($value, self::$functionMessage);
    }

    public static function getColor($value): ?string
    {
        return self::getLocalizedAnnotation($value, self::$functionColor);
    }

    public static function getAnnotationOne($value, string $annotationName): ?string
    {
        return self::getLocalizedAnnotation($value, $annotationName);
    }

    public static function getAnnotationList($value): ?array
    {

        $info = self::getFromValueInfo($value);

        if (is_null($info)) return null;

        return $info['annotation'];
    }

    protected static function getLocalizedAnnotation($value, $annotationName): ?string
    {

        $info = self::getFromValueInfo($value);

        if (is_null($info)) return null;

        $annotation = null;
        foreach ($info['annotation'] as $item) {
            if ($item['function'] === $annotationName) $annotation = $item;
        }

        if (is_null($annotation)) return null;

        $description = $annotation['value'] ?? '';
        switch ($annotation['function']) {
            case self::$functionMessage:
            case self::$functionColor:
            default:
                $result = static::message($description, $info['key'], $info['value']);
                break;
        }

        return $result;
    }

    public static function getFromValueInfo($value)
    {
        return array_column(static::getConstants(), null, 'value')[$value] ?? null;
    }

    public static function toSelectArray(): array
    {
        $array = static::toArray();
        $selectArray = [];
        foreach ($array as $value) {
            $selectArray[$value] = static::getDescription($value);
        }
        return $selectArray;
    }

    public static function getInstance($enumValue): self
    {
        return static::fromValue($enumValue);
    }

    public static function getInstances(): array
    {
        return array_map(
            function ($constantValue) {
                return new static($constantValue);
            },
            array_column(static::getConstants(), 'value')
        );
    }

    public static function getKeys(): array
    {
        return array_keys(static::getConstants());
    }

    /**
     * 检查定义的常量中是否包含某个「常量名称」
     * @param $key
     * @param bool $strict
     * @return bool
     */
    public static function hasKey($key, bool $strict = true): bool
    {
        $validKeys = static::getKeys();
        if ($strict) {
            return in_array($key, $validKeys, true);
        }

        return in_array(strtolower((string)$key), array_map('strtolower', $validKeys), true);
    }

    public static function fromKey(string $key, bool $strict = true): ?self
    {
        if (!static::hasKey($key, $strict)) {
            return null;
        }

        $key = $strict ? $key : strtoupper($key);
        $enumValue = static::getValue($key);

        return new static($enumValue, $strict);
    }

    public static function fromValue($enumValue, bool $strict = true): self
    {
        if ($enumValue instanceof static) {
            return $enumValue;
        }

        return new static($enumValue, $strict);
    }

    public static function getValue(string $key)
    {
        return static::getConstants()[$key]['value'] ?? null;
    }

    public static function make($enumKeyOrValue, bool $strict = true)
    {
        if ($enumKeyOrValue instanceof static) {
            return $enumKeyOrValue;
        }

        if (static::hasValue($enumKeyOrValue, $strict)) {
            return static::fromValue($enumKeyOrValue, $strict);
        }

        if (static::hasKey($enumKeyOrValue, $strict)) {
            return static::fromKey($enumKeyOrValue, $strict);
        }

        return $enumKeyOrValue;
    }

    /**
     * 解析注解枚举Message
     * @param mixed $description 描述
     * @param mixed $key key
     * @param mixed $value 值
     * @return mixed
     */
    protected static function message($description, $key, $value)
    {
        return $description;
    }

    public static function toArray(): array
    {
        $constants = static::getConstants();
        $result = [];
        foreach ($constants as $index => $constant) {
            $result[$index] = $constant['value'];
        }
        return $result;
    }

    public function __call($method, $parameters)
    {
        return self::__callStatic($method, $parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        return static::fromKey($method);
    }
}
