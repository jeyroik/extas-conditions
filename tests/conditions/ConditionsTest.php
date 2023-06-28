<?php
namespace tests\conditions;

use \PHPUnit\Framework\TestCase;
use extas\components\conditions\ConditionLikeOneIn;
use extas\components\conditions\ConditionNotLikeOneIn;
use extas\components\conditions\ConditionNotRegEx;
use extas\components\conditions\ConditionRegEx;
use extas\components\extensions\Extension;
use extas\components\extensions\ExtensionHasCondition;
use extas\components\Item;
use extas\interfaces\conditions\IHasCondition;
use extas\interfaces\extensions\IExtensionHasCondition;
use extas\components\conditions\ConditionAnd;
use extas\components\conditions\ConditionOr;
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
use extas\components\conditions\ConditionParameter;
use extas\components\repositories\RepoItem;
use extas\components\repositories\TSnuffRepository;

/**
 * Class ConditionsTest
 *
 * @author jeyroik@gmail.com
 */
class ConditionsTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        putenv("EXTAS__CONTAINER_PATH_STORAGE_LOCK=vendor/jeyroik/extas-foundation/resources/container.dist.json");
        $this->buildBasicRepos();
        $this->buildRepo(__DIR__ . '/../../vendor/jeyroik/extas-foundation/resources/', [
            'conditions' => [
                "namespace" => "tests\\tmp",
                "item_class" => "extas\\components\\conditions\\Condition",
                "pk" => "name",
                "aliases" => ["conditions"],
                "hooks" => [],
                "code" => [
                    'create-before' => '\\' . RepoItem::class . '::setId($item);'
                                    .'\\' . RepoItem::class . '::throwIfExist($this, $item, [\'name\']);'
                                    .'\\' . RepoItem::class . '::addNameToAliases($item);'
                ]
            ]
        ]);
    }

    public function tearDown(): void
    {
        $this->dropDatabase(__DIR__);
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
        $this->deleteRepo('conditions');
    }

    public function testExtensionHasCondition()
    {
        $cond = new Condition();
        $cond->extensions()->create(new Extension([
            Extension::FIELD__CLASS => ExtensionHasCondition::class,
            Extension::FIELD__INTERFACE => IExtensionHasCondition::class,
            Extension::FIELD__METHODS => [
                'isConditionMet',
                'getConditionName',
                'getCondition',
                'setConditionName'
            ],
            Extension::FIELD__SUBJECT => 'test'
        ]));

        /**
         * @var IExtensionHasCondition $test
         */
        $test = new class([
            IHasCondition::FIELD__VALUE => 5,
            IHasCondition::FIELD__CONDITION => 'eq'
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->installCondition('eq', ['='], ConditionEqual::class);
        $this->assertTrue($test->isConditionMet(5));
        $this->assertEquals('eq', $test->getConditionName());
        $test->setConditionName('=');
        $this->assertEquals('eq', $test->getCondition()->getName());
    }

    public function testExtensionHasConditionFailIsConditionMet()
    {
        $cond = new Condition();
        $cond->extensions()->create(new Extension([
            Extension::FIELD__CLASS => ExtensionHasCondition::class,
            Extension::FIELD__INTERFACE => IExtensionHasCondition::class,
            Extension::FIELD__METHODS => [
                'isConditionMet',
                'getConditionName',
                'getCondition',
                'setConditionName'
            ],
            Extension::FIELD__SUBJECT => 'test'
        ]));

        /**
         * @var IExtensionHasCondition $test
         */
        $test = new class() extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->expectExceptionMessage('Missed ' . IHasCondition::FIELD__CONDITION . ' parameter');
        $test->isConditionMet(5);
    }

    public function testExtensionHasConditionFailGetConditionName()
    {
        $cond = new Condition();
        $cond->extensions()->create(new Extension([
            Extension::FIELD__CLASS => ExtensionHasCondition::class,
            Extension::FIELD__INTERFACE => IExtensionHasCondition::class,
            Extension::FIELD__METHODS => [
                'isConditionMet',
                'getConditionName',
                'getCondition',
                'setConditionName'
            ],
            Extension::FIELD__SUBJECT => 'test'
        ]));

        /**
         * @var IExtensionHasCondition $test
         */
        $test = new class() extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->expectExceptionMessage('Missed ' . IHasCondition::FIELD__CONDITION . ' parameter');
        $test->getConditionName();
    }

    public function testExtensionHasConditionFailGetCondition()
    {
        $cond = new Condition();
        $cond->extensions()->create(new Extension([
            Extension::FIELD__CLASS => ExtensionHasCondition::class,
            Extension::FIELD__INTERFACE => IExtensionHasCondition::class,
            Extension::FIELD__METHODS => [
                'isConditionMet',
                'getConditionName',
                'getCondition',
                'setConditionName'
            ],
            Extension::FIELD__SUBJECT => 'test'
        ]));

        /**
         * @var IExtensionHasCondition $test
         */
        $test = new class() extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->expectExceptionMessage('Missed ' . IHasCondition::FIELD__CONDITION . ' parameter');
        $test->getCondition();
    }

    public function testUnknownCondition()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'es',
            ConditionParameter::FIELD__CONDITION => 'unknown'
        ]);

        $this->expectExceptionMessage('Unknown condition "unknown"');
        $hasCondition->isConditionMet('test');
    }

    public function testRegEx()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => '/@test(.*)/',
            ConditionParameter::FIELD__CONDITION => '#'
        ]);

        $this->installCondition('regex', ['#'], ConditionRegEx::class);

        $this->assertTrue($hasCondition->isConditionMet('@test me please'));
        $this->assertFalse($hasCondition->isConditionMet('tt'));

        $hasCondition->setConditionName('regex');
        $this->assertTrue($hasCondition->isConditionMet('@test again'));
    }

    public function testNotRegEx()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => '/@test(.*)/',
            ConditionParameter::FIELD__CONDITION => '!#'
        ]);

        $this->installCondition('not_regex', ['!#'], ConditionNotRegEx::class);

        $this->assertFalse($hasCondition->isConditionMet('@test me please'));
        $this->assertTrue($hasCondition->isConditionMet('tt'));

        $hasCondition->setConditionName('not_regex');
        $this->assertTrue($hasCondition->isConditionMet('again'));
    }

    public function testLikeOneIn()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => [
                'es', 5
            ],
            ConditionParameter::FIELD__CONDITION => '~*'
        ]);

        $this->installCondition('like_one_in', ['~*'], ConditionLikeOneIn::class);

        $this->assertTrue($hasCondition->isConditionMet('test'));
        $this->assertTrue($hasCondition->isConditionMet(55));
        $this->assertFalse($hasCondition->isConditionMet(7));

        $hasCondition->setValue('test');
        $this->assertTrue($hasCondition->isConditionMet('tester'));

        $hasCondition->setConditionName('like_one_in');
        $this->assertTrue($hasCondition->isConditionMet('test'));
    }

    public function testNotLikeOneIn()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => [
                'es', 5
            ],
            ConditionParameter::FIELD__CONDITION => '!~*'
        ]);

        $this->installCondition('not_like_one_in', ['!~*'], ConditionNotLikeOneIn::class);

        $this->assertFalse($hasCondition->isConditionMet('test'));
        $this->assertFalse($hasCondition->isConditionMet(55));
        $this->assertTrue($hasCondition->isConditionMet(7));

        $hasCondition->setValue('test');
        $this->assertFalse($hasCondition->isConditionMet('tester'));

        $hasCondition->setConditionName('not_like_one_in');
        $this->assertFalse($hasCondition->isConditionMet('test'));
    }

    public function testLike()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'es',
            ConditionParameter::FIELD__CONDITION => '~'
        ]);

        $this->installCondition('like', ['~'], ConditionLike::class);

        $this->assertTrue($hasCondition->isConditionMet('test'));
        $this->assertFalse($hasCondition->isConditionMet('tt'));

        $hasCondition->setValue(5);
        $this->assertTrue($hasCondition->isConditionMet(50));

        $hasCondition->setConditionName('like');
        $this->assertTrue($hasCondition->isConditionMet(105));
    }

    public function testNotLike()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'es',
            ConditionParameter::FIELD__CONDITION => '!~'
        ]);

        $this->installCondition('not_like', ['!~'], ConditionNotLike::class);

        $this->assertTrue($hasCondition->isConditionMet('tt'));
        $this->assertFalse($hasCondition->isConditionMet('test'));

        $hasCondition->setValue(5);
        $this->assertTrue($hasCondition->isConditionMet(60));

        $hasCondition->setConditionName('not_like');
        $this->assertTrue($hasCondition->isConditionMet(100));
    }

    public function testEmpty()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => '',
            ConditionParameter::FIELD__CONDITION => '@'
        ]);

        $this->installCondition('empty', ['null', '@'], ConditionEmpty::class);

        $this->assertTrue($hasCondition->isConditionMet(null));
        $this->assertFalse($hasCondition->isConditionMet(''));
        $this->assertFalse($hasCondition->isConditionMet(0));
        $this->assertFalse($hasCondition->isConditionMet(false));
        $this->assertFalse($hasCondition->isConditionMet(6));

        $hasCondition->setConditionName('empty');
        $this->assertTrue($hasCondition->isConditionMet(null));
    }

    public function testNotEmpty()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => '',
            ConditionParameter::FIELD__CONDITION => '!@'
        ]);

        $this->installCondition('not_empty', ['!null', '!@'], ConditionNotEmpty::class);

        $this->assertTrue($hasCondition->isConditionMet(6));
        $this->assertTrue($hasCondition->isConditionMet(''));
        $this->assertTrue($hasCondition->isConditionMet(0));
        $this->assertTrue($hasCondition->isConditionMet(false));
        $this->assertFalse($hasCondition->isConditionMet(null));

        $hasCondition->setConditionName('not_empty');
        $this->assertTrue($hasCondition->isConditionMet(true));
    }

    public function testAnd()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => [
                [
                    'value' => 5,
                    'condition' => '>'
                ],
                [
                    'value' => 10,
                    'condition' => '<'
                ]
            ],
            ConditionParameter::FIELD__CONDITION => '&'
        ]);

        $this->installCondition('and', ['&', '&&'], ConditionAnd::class);
        $this->installCondition('greater', ['>'], ConditionGreater::class);
        $this->installCondition('lower', ['<'], ConditionLower::class);

        $this->assertTrue($hasCondition->isConditionMet('6'));
        $this->assertTrue($hasCondition->isConditionMet(7));
        $this->assertFalse($hasCondition->isConditionMet(5));

        $hasCondition->setConditionName('and');
        $this->assertTrue($hasCondition->isConditionMet(8));

        $hasCondition->setValue('not array');
        $this->expectExceptionMessage('Need array as argument in a condition');
        $hasCondition->isConditionMet(8);
    }

    public function testOr()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => [
                [
                    'value' => 5,
                    'condition' => '>'
                ],
                [
                    'value' => 10,
                    'condition' => '<'
                ]
            ],
            ConditionParameter::FIELD__CONDITION => '||'
        ]);

        $this->installCondition('or', ['|', '||'], ConditionOr::class);
        $this->installCondition('greater', ['>'], ConditionGreater::class);
        $this->installCondition('lower', ['<'], ConditionLower::class);

        $this->assertTrue($hasCondition->isConditionMet('6'));
        $this->assertTrue($hasCondition->isConditionMet(4));
        $this->assertTrue($hasCondition->isConditionMet(11));

        $hasCondition->setConditionName('or');
        $this->assertTrue($hasCondition->isConditionMet(8));

        $hasCondition->setValue('not array');
        $this->expectExceptionMessage('Need array as argument in a condition');
        $hasCondition->isConditionMet(8);
    }

    public function testIn()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => [
                'test', 5
            ],
            ConditionParameter::FIELD__CONDITION => '*'
        ]);

        $this->installCondition('in', ['*'], ConditionIn::class);

        $this->assertTrue($hasCondition->isConditionMet('test'));
        $this->assertTrue($hasCondition->isConditionMet(5));
        $this->assertTrue($hasCondition->isConditionMet(['test']));
        $this->assertTrue($hasCondition->isConditionMet([5, 'test']));
        $this->assertFalse($hasCondition->isConditionMet(7));

        $hasCondition->setValue('test');
        $this->assertTrue($hasCondition->isConditionMet('test'));

        $hasCondition->setConditionName('in');
        $this->assertTrue($hasCondition->isConditionMet('test'));
    }

    public function testNotIn()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => [
                'test', 5
            ],
            ConditionParameter::FIELD__CONDITION => '!*'
        ]);

        $this->installCondition('not_in', ['!*'], ConditionNotIn::class);

        $this->assertTrue($hasCondition->isConditionMet('test1'));
        $this->assertTrue($hasCondition->isConditionMet(50));
        $this->assertTrue($hasCondition->isConditionMet(['test1']));
        $this->assertTrue($hasCondition->isConditionMet([50, 'test1']));
        $this->assertTrue($hasCondition->isConditionMet(7));

        $hasCondition->setValue('test1');
        $this->assertTrue($hasCondition->isConditionMet('test'));

        $hasCondition->setConditionName('not_in');
        $this->assertTrue($hasCondition->isConditionMet('test'));
    }

    public function testEqual()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 5,
            ConditionParameter::FIELD__CONDITION => '='
        ]);

        $this->installCondition('equal', ['='], ConditionEqual::class);

        $this->assertTrue($hasCondition->isConditionMet(5));
        $this->assertTrue($hasCondition->isConditionMet('5'));
        $this->assertFalse($hasCondition->isConditionMet(6));

        $hasCondition->setConditionName('equal');
        $this->assertTrue($hasCondition->isConditionMet(5));
    }

    public function testEqualLength()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abcde',
            ConditionParameter::FIELD__CONDITION => 'l='
        ]);

        $this->installCondition('equal_length', ['l='], ConditionEqualLength::class);

        $this->assertTrue($hasCondition->isConditionMet('fghij'));
        $this->assertFalse($hasCondition->isConditionMet('abc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(70));

        $hasCondition->setConditionName('equal_length');
        $this->assertTrue($hasCondition->isConditionMet('ab'));
    }

    public function testEqualAlphabet()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'a='
        ]);

        $this->installCondition('equal_alphabet', ['a='], ConditionEqualAlphabet::class);

        $this->assertTrue($hasCondition->isConditionMet('abc'));
        $this->assertFalse($hasCondition->isConditionMet('acb'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(50));

        $hasCondition->setConditionName('equal_alphabet');
        $this->assertTrue($hasCondition->isConditionMet(50));
    }

    public function testNotEqual()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 5,
            ConditionParameter::FIELD__CONDITION => '!='
        ]);

        $this->installCondition('not_equal', ['!='], ConditionNotEqual::class);

        $this->assertTrue($hasCondition->isConditionMet(6));
        $this->assertTrue($hasCondition->isConditionMet('6'));
        $this->assertFalse($hasCondition->isConditionMet(5));

        $hasCondition->setConditionName('not_equal');
        $this->assertTrue($hasCondition->isConditionMet(6));
    }

    public function testNotEqualLength()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abcde',
            ConditionParameter::FIELD__CONDITION => 'l!='
        ]);

        $this->installCondition('not_equal_length', ['l!='], ConditionNotEqualLength::class);

        $this->assertTrue($hasCondition->isConditionMet('abcdef'));
        $this->assertFalse($hasCondition->isConditionMet('fghij'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(500));

        $hasCondition->setConditionName('not_equal_length');
        $this->assertTrue($hasCondition->isConditionMet('test'));
    }

    public function testNotEqualAlphabet()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'a!='
        ]);

        $this->installCondition('not_equal_alphabet', ['a!='], ConditionNotEqualAlphabet::class);

        $this->assertTrue($hasCondition->isConditionMet('bac'));
        $this->assertFalse($hasCondition->isConditionMet('abc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(51));

        $hasCondition->setConditionName('not_equal_alphabet');
        $this->assertTrue($hasCondition->isConditionMet('cab'));
    }

    public function testGreater()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 5,
            ConditionParameter::FIELD__CONDITION => '>'
        ]);

        $this->installCondition('greater', ['>'], ConditionGreater::class);

        $this->assertTrue($hasCondition->isConditionMet(6));
        $this->assertTrue($hasCondition->isConditionMet('50'));
        $this->assertFalse($hasCondition->isConditionMet(5));

        $hasCondition->setConditionName('greater');
        $this->assertTrue($hasCondition->isConditionMet(50));
    }

    public function testGreaterLength()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'l>'
        ]);

        $this->installCondition('greater_length', ['l>'], ConditionGreaterLength::class);

        $this->assertTrue($hasCondition->isConditionMet('abcd'));
        $this->assertFalse($hasCondition->isConditionMet('ab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(0.001));

        $hasCondition->setConditionName('greater_length');
        $this->assertTrue($hasCondition->isConditionMet('test'));
    }

    public function testGreaterAlphabet()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'a>'
        ]);

        $this->installCondition('greater_alphabet', ['a>'], ConditionGreaterAlphabet::class);

        $this->assertTrue($hasCondition->isConditionMet('bbc'));
        $this->assertFalse($hasCondition->isConditionMet('aab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(60));

        $hasCondition->setConditionName('greater_alphabet');
        $this->assertTrue($hasCondition->isConditionMet(51));
    }

    public function testGreaterOrEqual()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 5,
            ConditionParameter::FIELD__CONDITION => '>='
        ]);

        $this->installCondition('greater_or_equal', ['>='], ConditionGreaterOrEqual::class);

        $this->assertTrue($hasCondition->isConditionMet(6));
        $this->assertTrue($hasCondition->isConditionMet('5'));
        $this->assertFalse($hasCondition->isConditionMet(4));

        $hasCondition->setConditionName('greater_or_equal');
        $this->assertTrue($hasCondition->isConditionMet(50));
    }

    public function testGreaterOrEqualLength()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'l>='
        ]);

        $this->installCondition('greater_or_equal_length', ['l>='], ConditionGreaterOrEqualLength::class);

        $this->assertTrue($hasCondition->isConditionMet('abc'));
        $this->assertTrue($hasCondition->isConditionMet('abcd'));
        $this->assertFalse($hasCondition->isConditionMet('ab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(0.001));

        $hasCondition->setConditionName('greater_or_equal_length');
        $this->assertTrue($hasCondition->isConditionMet(500));
    }

    public function testGreaterOrEqualAlphabet()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'a>='
        ]);

        $this->installCondition(
            'greater_or_equal_alphabet',
            ['a>='],
            ConditionGreaterOrEqualAlphabet::class
        );

        $this->assertTrue($hasCondition->isConditionMet('bbc'));
        $this->assertTrue($hasCondition->isConditionMet('abc'));
        $this->assertFalse($hasCondition->isConditionMet('aab'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(60));

        $hasCondition->setConditionName('greater_or_equal_alphabet');
        $this->assertTrue($hasCondition->isConditionMet(50));
    }

    public function testLower()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 5,
            ConditionParameter::FIELD__CONDITION => '<'
        ]);

        $this->installCondition('lower', ['<'], ConditionLower::class);

        $this->assertTrue($hasCondition->isConditionMet(4));
        $this->assertTrue($hasCondition->isConditionMet('3'));
        $this->assertFalse($hasCondition->isConditionMet(5));

        $hasCondition->setConditionName('lower');
        $this->assertTrue($hasCondition->isConditionMet(1));
    }

    public function testLowerLength()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'l<'
        ]);

        $this->installCondition('lower_length', ['l<'], ConditionLowerLength::class);

        $this->assertTrue($hasCondition->isConditionMet('ab'));
        $this->assertFalse($hasCondition->isConditionMet('abc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(9));

        $hasCondition->setConditionName('lower_length');
        $this->assertTrue($hasCondition->isConditionMet(5));
    }

    public function testLowerAlphabet()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'a<'
        ]);

        $this->installCondition('lower_alphabet', ['a<'], ConditionLowerAlphabet::class);

        $this->assertTrue($hasCondition->isConditionMet('aac'));
        $this->assertFalse($hasCondition->isConditionMet('bbc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(40));

        $hasCondition->setConditionName('lower_alphabet');
        $this->assertTrue($hasCondition->isConditionMet(400));
    }

    public function testLowerOrEqual()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 5,
            ConditionParameter::FIELD__CONDITION => '<='
        ]);

        $this->installCondition('lower_or_equal', ['<='], ConditionLowerOrEqual::class);

        $this->assertTrue($hasCondition->isConditionMet(4));
        $this->assertTrue($hasCondition->isConditionMet('5'));
        $this->assertFalse($hasCondition->isConditionMet(6));

        $hasCondition->setConditionName('lower_or_equal');
        $this->assertTrue($hasCondition->isConditionMet(1));
    }

    public function testLowerOrEqualLength()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'l<='
        ]);

        $this->installCondition('lower_or_equal_length', ['l<='], ConditionLowerOrEqualLength::class);

        $this->assertTrue($hasCondition->isConditionMet('ab'));
        $this->assertTrue($hasCondition->isConditionMet('abr'));
        $this->assertFalse($hasCondition->isConditionMet('test'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(50));

        $hasCondition->setConditionName('lower_or_equal_length');
        $this->assertTrue($hasCondition->isConditionMet(5));
    }

    public function testLowerOrEqualAlphabet()
    {
        $hasCondition = new ConditionParameter ([
            ConditionParameter::FIELD__VALUE => 'abc',
            ConditionParameter::FIELD__CONDITION => 'a<='
        ]);

        $this->installCondition('lower_or_equal_alphabet', ['a<='], ConditionLowerOrEqualAlphabet::class);

        $this->assertTrue($hasCondition->isConditionMet('aac'));

        $this->assertTrue($hasCondition->isConditionMet('abc'));
        $this->assertFalse($hasCondition->isConditionMet('bbc'));

        $hasCondition->setValue(50);
        $this->assertTrue($hasCondition->isConditionMet(40));

        $hasCondition->setConditionName('lower_or_equal_alphabet');
        $this->assertTrue($hasCondition->isConditionMet(400));
    }

    /**
     * @param string $name
     * @param array $aliases
     * @param string $class
     */
    protected function installCondition(string $name, array $aliases, string $class)
    {
        $cond = new Condition();
        $cond->conditions()->create(new Condition([
            Condition::FIELD__NAME => $name,
            Condition::FIELD__ALIASES => $aliases,
            Condition::FIELD__CLASS => $class,
            Condition::FIELD__TITLE => 'test'
        ]));
    }
}
