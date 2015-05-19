<?php

use Memio\Memio\Config\Build;
use Memio\Model\Argument;
use Memio\Model\Constant;
use Memio\Model\Method;
use Memio\Model\Object;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/autocomplete-generator/src/AutoCompleteGenerator.php';

/**
 * Example:
 *
 *  php autocomplete.php atphp:ag Gmagick GmagickDraw GmagickPixel
 *
 */

function main()
{
    global $argv;

    foreach ($argv as $arg) {
        if (class_exists($arg)) {
            echo atphp_autocomplete_generate($arg) . "\n\n";
        }
    }
}

function atphp_autocomplete_generate($className)
{
    $ref = new ReflectionClass($className);

    /** @var Object $class */
    $object = new Object($className);

    foreach ($ref->getConstants() as $k => $v) {
        $object->addConstant(Constant::make($k, $v));
    }

    foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $rMethod) {
        $method = Method::make($rMethod->getName());
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

main();

