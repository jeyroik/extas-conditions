![tests](https://github.com/jeyroik/extas-conditions/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-conditions/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a> 
<a href="https://codeclimate.com/github/jeyroik/extas-conditions/maintainability"><img src="https://api.codeclimate.com/v1/badges/75161f4b9667f6a7d3d6/maintainability" /></a>
<a href="https://github.com/jeyroik/extas-installer/" title="Extas Installer v3"><img alt="Extas Installer v3" src="https://img.shields.io/badge/installer-v3-green"></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-conditions/v)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-conditions/downloads)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Dependents](https://poser.pugx.org/jeyroik/extas-conditions/dependents)](//packagist.org/packages/jeyroik/extas-q-crawlers)

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
