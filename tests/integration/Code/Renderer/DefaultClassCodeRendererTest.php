<?php

namespace integration\Eventity\Code\Renderer;

use PHPUnit_Framework_TestCase;
use Eventity\Code\Renderer\DefaultClassCodeRenderer;
use Eventity\Code\Definition\ClassDefinition;
use Eventity\Code\Definition\FieldDefinition;
use Eventity\Code\Definition\MethodDefinition;
use Eventity\Code\Renderer\DefaultCodeRenderer;
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(\Eventity\Code\Definition\MethodDefinition::createPublic(
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

        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
            ->setClassName('TestClass')
            ->addMethod(\Eventity\Code\Definition\MethodDefinition::createPublicWithParams(
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

        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
        $definition = \Eventity\Code\Definition\ClassDefinition::builder()
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
