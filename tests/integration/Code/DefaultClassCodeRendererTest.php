<?php

namespace integration\Eventity\Code;

use PHPUnit_Framework_TestCase;
use Eventity\Code\DefaultClassCodeRenderer;
use Eventity\Code\ClassDefinition;
use Eventity\Code\FieldDefinition;
use Eventity\Code\MethodDefinition;
use Eventity\Code\DefaultCodeRenderer;
use Eventity\Code\Definition\ParameterDefinition;

final class DefaultClassCodeRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultClassCodeRenderer
     */
    private $renderer;

    function setUp()
    {
        $this->renderer = new DefaultClassCodeRenderer(new DefaultCodeRenderer());
    }

    /** @test */
    function it_is_creates_an_empty_class()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->build();

        $expected = <<<EOF
class TestClass
{
}

EOF;

        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    public function it_creates_class_with_a_namespace()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('Test\Namespace\NSClass')
            ->build();

        $expected = <<<EOF
namespace Test\Namespace;

class NSClass
{
}

EOF;

        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_creates_a_class_which_extends_a_namespaced_parent()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->setParent('Test\Namespace\Parent')
            ->build();

        $expected = <<<EOF
use Test\Namespace\Parent;

class TestClass extends Parent
{
}

EOF;

        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_creates_a_class_which_implements_interfaces()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addInterface('InterfaceOne')
            ->addInterface('InterfaceTwo')
            ->build();

        $expected = <<<EOF
class TestClass implements InterfaceOne, InterfaceTwo
{
}

EOF;

        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_creates_a_class_which_implements_a_namespaced_interface()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addInterface('Test\Namespace\TestInterface')
            ->build();

        $expected = <<<EOF
use Test\Namespace\TestInterface;

class TestClass implements TestInterface
{
}

EOF;

        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_creates_a_class_with_a_private_field()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addField(FieldDefinition::createPrivate('testField'))
            ->build();

        $expected = <<<EOF
class TestClass
{
    private \$testField;
}

EOF;

        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_creates_a_class_with_a_public_method()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(MethodDefinition::createPublic(
                'testMethod',
                'return "the body";'
            ))
            ->build();

        $expected = <<<EOF
class TestClass
{
    public function testMethod()
    {
        return "the body";
    }
}

EOF;
        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_creates_a_class_with_a_method_with_parameters()
    {
        $param1 = ParameterDefinition::create('param1');
        $param2 = ParameterDefinition::create('param2');

        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(MethodDefinition::createPublicWithParams(
                'testMethod',
                [$param1, $param2],
                'return "the body";'
            ))
            ->build();

        $expected = <<<EOF
class TestClass
{
    public function testMethod(\$param1, \$param2)
    {
        return "the body";
    }
}

EOF;
        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_creates_a_class_with_a_method_with_typed_parameters()
    {
        $parameter = ParameterDefinition::createWithType('array', 'paramName');

        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(MethodDefinition::createPublicWithParams(
                'testMethod',
                [$parameter],
                'return "the body";'
            ))
            ->build();

        $expected = <<<EOF
class TestClass
{
    public function testMethod(array \$paramName)
    {
        return "the body";
    }
}

EOF;
        $this->assertEquals($expected, $this->renderer->render($definition));
    }

    /** @test */
    function it_indents_multiline_method_bodies()
    {
        $definition = ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(MethodDefinition::createPublic(
                'testMethod',
                "\$line1;\n\$line2;"
            ))
            ->build();

        $expected = <<<EOF
class TestClass
{
    public function testMethod()
    {
        \$line1;
        \$line2;
    }
}

EOF;
        $this->assertEquals($expected, $this->renderer->render($definition));
    }
}
