<?php

namespace atphp\autocomplete_generator;

use Memio\Memio\Config\Build;
use Memio\Model\Argument;
use Memio\Model\Constant;
use Memio\Model\Method;
use Memio\Model\Object;

class AutocompleteGenerator
{

    public function generate($className)
    {
        $ref = new \ReflectionClass($className);

        /** @var Object $class */
        $object = new Object($className);

        foreach ($ref->getConstants() as $k => $v) {
            $object->addConstant(Constant::make($k, is_numeric($v) ? $v : '"' . $v . '"'));
        }

        foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $rMethod) {
            $method = Method::make($rMethod->getName());

            // Add modifiers
            $rMethod->isStatic() && $method->makeStatic();
            $rMethod->isAbstract() && $method->makeAbstract();
            $rMethod->isFinal() && $method->makeFinal();

            // Add params
            foreach ($rMethod->getParameters() as $rParam) {
                $type = 'string';
                if ($paramClass = $rParam->getClass()) {
                    $type = $paramClass->getName();
                }
                $method->addArgument(Argument::make($type, $rParam->getName()));
            }
            $object->addMethod($method);
        }

        $prettyPrinter = Build::prettyPrinter();
        return $prettyPrinter->generateCode($object);
    }

}
