![tests](https://github.com/jeyroik/extas-conditions/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-conditions/coverage.svg?branch=master) 
<a href="https://codeclimate.com/github/jeyroik/extas-conditions/maintainability"><img src="https://api.codeclimate.com/v1/badges/75161f4b9667f6a7d3d6/maintainability" /></a>

# Описание

Условия и их проверка.

# Использование

1. Установить обработчики.

`# vendor/bin/extas i`

2. Для использования проще всего реализовать интерфейс `extas\interfaces\conditions\IHasConditions`.

```php
$hasCondition = new class ([
        IHasValue::FIELD__VALUE => [
            [
                'value' => 5,
                'condition' => '>'
            ],
            [
                'value' => 10,
                'condition' => '<'
            ]
        ],
        IHasCondition::FIELD__CONDITION => '&'
    ]) extends Item implements IHasCondition {
        use THasCondition;
        use THasValue;
        protected function getSubjectForExtension(): string
        {
            return '';
        }
    };

 echo $hasCondition->isConditionTrue(7); // true
 echo $hasCondition->isConditionTrue(5); // false
```
