<?php
use \PHPUnit\Framework\TestCase;
use extas\components\Item;
use extas\interfaces\conditions\IHasCondition;
use extas\components\conditions\THasCondition;
use extas\components\THasValue;
use extas\interfaces\IHasValue;
use extas\components\conditions\ConditionIn;
use extas\components\conditions\ConditionNotIn;
use extas\components\conditions\ConditionEmpty;
use extas\components\conditions\ConditionNotEmpty;
use extas\components\conditions\ConditionLike;
use extas\components\conditions\ConditionNotLike;
use extas\components\conditions\ConditionEqual;
use extas\components\conditions\ConditionNotEqual;
use extas\components\conditions\ConditionEqualLength;
use extas\components\conditions\ConditionNotEqualLength;
use extas\components\conditions\ConditionEqualAlphabet;
use extas\components\conditions\ConditionNotEqualAlphabet;
use extas\components\conditions\ConditionGreater;
use extas\components\conditions\ConditionGreaterOrEqual;
use extas\components\conditions\ConditionGreaterLength;
use extas\components\conditions\ConditionGreaterOrEqualLength;
use extas\components\conditions\ConditionGreaterAlphabet;
use extas\components\conditions\ConditionGreaterOrEqualAlphabet;
use extas\components\conditions\ConditionLower;
use extas\components\conditions\ConditionLowerOrEqual;
use extas\components\conditions\ConditionLowerLength;
use extas\components\conditions\ConditionLowerOrEqualLength;
use extas\components\conditions\ConditionLowerAlphabet;
use extas\components\conditions\ConditionLowerOrEqualAlphabet;
use extas\components\conditions\Condition;
use extas\components\conditions\ConditionRepository;
use extas\interfaces\conditions\IConditionRepository;
use extas\components\SystemContainer;
use extas\interfaces\repositories\IRepository;
use extas\components\plugins\PluginRepository;
use extas\components\plugins\Plugin;
use extas\components\plugins\repositories\PluginFieldSelfAlias;

/**
 * Class ConditionsTest
 *
 * @author jeyroik@gmail.com
 */
class ConditionsTest extends TestCase
{
    /**
     * @var IRepository|null
     */
    protected ?IRepository $condRepo = null;

    /**
     * @var IRepository|null
     */
    protected ?IRepository $pluginRepo = null;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->condRepo = new ConditionRepository();
        $this->pluginRepo = new PluginRepository;

        SystemContainer::addItem(
            IConditionRepository::class,
            ConditionRepository::class
        );
    }

    public function tearDown(): void
    {
        $this->condRepo->delete([Condition::FIELD__NAME => 'test']);
        $this->pluginRepo->delete([Plugin::FIELD__CLASS => PluginFieldSelfAlias::class]);
    }

    public function testLike()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'es',
            IHasCondition::FIELD__CONDITION => '~'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('like', ['~'], ConditionLike::class);

        $this->assertTrue($hasCondition->isConditionTrue('test'));
        $this->assertFalse($hasCondition->isConditionTrue('tt'));

        $hasCondition->setValue(5);
        $this->assertTrue($hasCondition->isConditionTrue(50));

        $hasCondition->setConditionName('like');
        $this->assertTrue($hasCondition->isConditionTrue(105));
    }

    public function testNotLike()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'es',
            IHasCondition::FIELD__CONDITION => '!~'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('not_like', ['!~'], ConditionNotLike::class);

        $this->assertTrue($hasCondition->isConditionTrue('tt'));
        $this->assertFalse($hasCondition->isConditionTrue('test'));

        $hasCondition->setValue(5);
        $this->assertTrue($hasCondition->isConditionTrue(60));

        $hasCondition->setConditionName('not_like');
        $this->assertTrue($hasCondition->isConditionTrue(100));
    }

    public function testEmpty()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => '',
            IHasCondition::FIELD__CONDITION => '@'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('empty', ['null', '@'], ConditionEmpty::class);

        $this->assertTrue($hasCondition->isConditionTrue(null));
        $this->assertFalse($hasCondition->isConditionTrue(''));
        $this->assertFalse($hasCondition->isConditionTrue(0));
        $this->assertFalse($hasCondition->isConditionTrue(false));
        $this->assertFalse($hasCondition->isConditionTrue(6));

        $hasCondition->setConditionName('empty');
        $this->assertTrue($hasCondition->isConditionTrue(null));
    }

    public function testNotEmpty()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => '',
            IHasCondition::FIELD__CONDITION => '!@'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('not_empty', ['!null', '!@'], ConditionNotEmpty::class);

        $this->assertTrue($hasCondition->isConditionTrue(6));
        $this->assertTrue($hasCondition->isConditionTrue(''));
        $this->assertTrue($hasCondition->isConditionTrue(0));
        $this->assertTrue($hasCondition->isConditionTrue(false));
        $this->assertFalse($hasCondition->isConditionTrue(null));

        $hasCondition->setConditionName('not_empty');
        $this->assertTrue($hasCondition->isConditionTrue(true));
    }

    public function testAnd()
    {
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

        $this->installCondition('and', ['&', '&&'], ConditionIn::class);

        $this->assertTrue($hasCondition->isConditionTrue('6'));
        $this->assertTrue($hasCondition->isConditionTrue(7));
        $this->assertFalse($hasCondition->isConditionTrue(5));

        $hasCondition->setConditionName('and');
        $this->assertTrue($hasCondition->isConditionTrue(8));
    }

    public function testOr()
    {
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
            IHasCondition::FIELD__CONDITION => '||'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('or', ['|', '||'], ConditionIn::class);

        $this->assertTrue($hasCondition->isConditionTrue('6'));
        $this->assertTrue($hasCondition->isConditionTrue(4));
        $this->assertTrue($hasCondition->isConditionTrue(11));

        $hasCondition->setConditionName('or');
        $this->assertTrue($hasCondition->isConditionTrue(8));
    }

    public function testIn()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => [
                'test', 5
            ],
            IHasCondition::FIELD__CONDITION => '*'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('in', ['*'], ConditionIn::class);

        $this->assertTrue($hasCondition->isConditionTrue('test'));
        $this->assertTrue($hasCondition->isConditionTrue(5));
        $this->assertTrue($hasCondition->isConditionTrue(['test']));
        $this->assertTrue($hasCondition->isConditionTrue([5, 'test']));
        $this->assertFalse($hasCondition->isConditionTrue(7));

        $hasCondition->setValue('test');
        $this->assertTrue($hasCondition->isConditionTrue('test'));

        $hasCondition->setConditionName('in');
        $this->assertTrue($hasCondition->isConditionTrue('test'));
    }

    public function testNotIn()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => [
                'test', 5
            ],
            IHasCondition::FIELD__CONDITION => '!*'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('not_in', ['!*'], ConditionNotIn::class);

        $this->assertTrue($hasCondition->isConditionTrue('test1'));
        $this->assertTrue($hasCondition->isConditionTrue(50));
        $this->assertTrue($hasCondition->isConditionTrue(['test1']));
        $this->assertTrue($hasCondition->isConditionTrue([50, 'test1']));
        $this->assertTrue($hasCondition->isConditionTrue(7));

        $hasCondition->setValue('test1');
        $this->assertTrue($hasCondition->isConditionTrue('test'));

        $hasCondition->setConditionName('not_in');
        $this->assertTrue($hasCondition->isConditionTrue('test'));
    }

    public function testEqual()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 5,
            IHasCondition::FIELD__CONDITION => '='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('equal', ['='], ConditionEqual::class);

        $this->assertTrue($hasCondition->isConditionTrue(5));
        $this->assertTrue($hasCondition->isConditionTrue('5'));
        $this->assertFalse($hasCondition->isConditionTrue(6));

        $hasCondition->setConditionName('equal');
        $this->assertTrue($hasCondition->isConditionTrue(5));
    }

    public function testEqualLength()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abcde',
            IHasCondition::FIELD__CONDITION => 'l='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('equal_length', ['l='], ConditionEqualLength::class);

        $this->assertTrue($hasCondition->isConditionTrue('fghij'));
        $this->assertFalse($hasCondition->isConditionTrue('abc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(70));

        $hasCondition->setConditionName('equal_length');
        $this->assertTrue($hasCondition->isConditionTrue('edcba'));
    }

    public function testEqualAlphabet()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'a='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('equal_alphabet', ['a='], ConditionEqualAlphabet::class);

        $this->assertTrue($hasCondition->isConditionTrue('abc'));
        $this->assertFalse($hasCondition->isConditionTrue('acb'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(50));

        $hasCondition->setConditionName('equal_alphabet');
        $this->assertTrue($hasCondition->isConditionTrue('abc'));
    }

    public function testNotEqual()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 5,
            IHasCondition::FIELD__CONDITION => '!='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('not_equal', ['!='], ConditionNotEqual::class);

        $this->assertTrue($hasCondition->isConditionTrue(6));
        $this->assertTrue($hasCondition->isConditionTrue('6'));
        $this->assertFalse($hasCondition->isConditionTrue(5));

        $hasCondition->setConditionName('not_equal');
        $this->assertTrue($hasCondition->isConditionTrue(6));
    }

    public function testNotEqualLength()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abcde',
            IHasCondition::FIELD__CONDITION => 'l!='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('not_equal_length', ['l!='], ConditionNotEqualLength::class);

        $this->assertTrue($hasCondition->isConditionTrue('abcdef'));
        $this->assertFalse($hasCondition->isConditionTrue('fghij'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(50));

        $hasCondition->setConditionName('not_equal_length');
        $this->assertTrue($hasCondition->isConditionTrue('fedcba'));
    }

    public function testNotEqualAlphabet()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'a!='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('not_equal_alphabet', ['a!='], ConditionNotEqualAlphabet::class);

        $this->assertTrue($hasCondition->isConditionTrue('bac'));
        $this->assertFalse($hasCondition->isConditionTrue('abc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(51));

        $hasCondition->setConditionName('not_equal_alphabet');
        $this->assertTrue($hasCondition->isConditionTrue('cab'));
    }

    public function testGreater()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 5,
            IHasCondition::FIELD__CONDITION => '>'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('greater', ['>'], ConditionGreater::class);

        $this->assertTrue($hasCondition->isConditionTrue(6));
        $this->assertTrue($hasCondition->isConditionTrue('50'));
        $this->assertFalse($hasCondition->isConditionTrue(5));

        $hasCondition->setConditionName('greater');
        $this->assertTrue($hasCondition->isConditionTrue(50));
    }

    public function testGreaterLength()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'l>'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('greater_length', ['l>'], ConditionGreaterLength::class);

        $this->assertTrue($hasCondition->isConditionTrue('abcd'));
        $this->assertFalse($hasCondition->isConditionTrue('ab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(0.001));

        $hasCondition->setConditionName('greater_length');
        $this->assertTrue($hasCondition->isConditionTrue('test'));
    }

    public function testGreaterAlphabet()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'a>'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('greater_alphabet', ['a>'], ConditionGreaterAlphabet::class);

        $this->assertTrue($hasCondition->isConditionTrue('bbc'));
        $this->assertFalse($hasCondition->isConditionTrue('aab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(60));

        $hasCondition->setConditionName('greater_alphabet');
        $this->assertTrue($hasCondition->isConditionTrue(51));
    }

    public function testGreaterOrEqual()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 5,
            IHasCondition::FIELD__CONDITION => '>='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('greater_or_equal', ['>='], ConditionGreaterOrEqual::class);

        $this->assertTrue($hasCondition->isConditionTrue(6));
        $this->assertTrue($hasCondition->isConditionTrue('5'));
        $this->assertFalse($hasCondition->isConditionTrue(4));

        $hasCondition->setConditionName('greater_or_equal');
        $this->assertTrue($hasCondition->isConditionTrue(50));
    }

    public function testGreaterOrEqualLength()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'l>='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('greater_or_equal_length', ['l>='], ConditionGreaterOrEqualLength::class);

        $this->assertTrue($hasCondition->isConditionTrue('abc'));
        $this->assertTrue($hasCondition->isConditionTrue('abcd'));
        $this->assertFalse($hasCondition->isConditionTrue('ab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(0.001));

        $hasCondition->setConditionName('greater_or_equal_length');
        $this->assertTrue($hasCondition->isConditionTrue(500));
    }

    public function testGreaterOrEqualAlphabet()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'a>='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition(
            'greater_or_equal_alphabet',
            ['a>='],
            ConditionGreaterOrEqualAlphabet::class
        );

        $this->assertTrue($hasCondition->isConditionTrue('bbc'));
        $this->assertTrue($hasCondition->isConditionTrue('abc'));
        $this->assertFalse($hasCondition->isConditionTrue('aab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(60));

        $hasCondition->setConditionName('greater_or_equal_alphabet');
        $this->assertTrue($hasCondition->isConditionTrue(50));
    }

    public function testLower()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 5,
            IHasCondition::FIELD__CONDITION => '<'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('lower', ['<'], ConditionLower::class);

        $this->assertTrue($hasCondition->isConditionTrue(4));
        $this->assertTrue($hasCondition->isConditionTrue('3'));
        $this->assertFalse($hasCondition->isConditionTrue(5));

        $hasCondition->setConditionName('lower');
        $this->assertTrue($hasCondition->isConditionTrue(1));
    }

    public function testLowerLength()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'l<'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('lower_length', ['l<'], ConditionLowerLength::class);

        $this->assertTrue($hasCondition->isConditionTrue('ab'));
        $this->assertFalse($hasCondition->isConditionTrue('abc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(9));

        $hasCondition->setConditionName('lower_length');
        $this->assertTrue($hasCondition->isConditionTrue(5));
    }

    public function testLowerAlphabet()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'a<'
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('lower_alphabet', ['a<'], ConditionLowerAlphabet::class);

        $this->assertTrue($hasCondition->isConditionTrue('aac'));
        $this->assertFalse($hasCondition->isConditionTrue('bbc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(40));

        $hasCondition->setConditionName('lower_alphabet');
        $this->assertTrue($hasCondition->isConditionTrue(400));
    }

    public function testLowerOrEqual()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 5,
            IHasCondition::FIELD__CONDITION => '<='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('lower_or_equal', ['<='], ConditionLowerOrEqual::class);

        $this->assertTrue($hasCondition->isConditionTrue(4));
        $this->assertTrue($hasCondition->isConditionTrue('5'));
        $this->assertFalse($hasCondition->isConditionTrue(6));

        $hasCondition->setConditionName('lower_or_equal');
        $this->assertTrue($hasCondition->isConditionTrue(1));
    }

    public function testLowerOrEqualLength()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'l<='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('lower_or_equal_length', ['l<='], ConditionLowerOrEqualLength::class);

        $this->assertTrue($hasCondition->isConditionTrue('ab'));
        $this->assertTrue($hasCondition->isConditionTrue('abr'));
        $this->assertFalse($hasCondition->isConditionTrue('test'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(50));

        $hasCondition->setConditionName('lower_or_equal_length');
        $this->assertTrue($hasCondition->isConditionTrue(5));
    }

    public function testLowerOrEqualAlphabet()
    {
        $hasCondition = new class ([
            IHasValue::FIELD__VALUE => 'abc',
            IHasCondition::FIELD__CONDITION => 'a<='
        ]) extends Item implements IHasCondition {
            use THasCondition;
            use THasValue;
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->installCondition('lower_or_equal_alphabet', ['a<='], ConditionLowerOrEqualAlphabet::class);

        $this->assertTrue($hasCondition->isConditionTrue('aac'));
        $this->assertTrue($hasCondition->isConditionTrue('abc'));
        $this->assertFalse($hasCondition->isConditionTrue('bbc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionTrue(40));

        $hasCondition->setConditionName('lower_or_equal_alphabet');
        $this->assertTrue($hasCondition->isConditionTrue(400));
    }



    /**
     * @param string $name
     * @param array $aliases
     * @param string $class
     */
    protected function installCondition(string $name, array $aliases, string $class)
    {
        $this->pluginRepo->create(new Plugin([
            Plugin::FIELD__CLASS => PluginFieldSelfAlias::class,
            Plugin::FIELD__STAGE => 'extas.conditions.create.before'
        ]));

        $this->condRepo->create(new Condition([
            Condition::FIELD__NAME => $name,
            Condition::FIELD__ALIASES => $aliases,
            Condition::FIELD__CLASS => $class
        ]));
    }
}
