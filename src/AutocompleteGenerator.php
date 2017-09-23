<?php

namespace atphp\autocomplete_generator;

use Memio\Memio\Config\Build;
use Memio\Model\Argument;
use Memio\Model\Constant;
use Memio\Model\File;
use Memio\Model\Method;
use Memio\Model\Object;
use ReflectionClass;
use ReflectionExtension;
use ReflectionMethod;

class AutocompleteGenerator
{
    private function baseDir($extension = '')
    {
        return rtrim(dirname(sys_get_temp_dir()) . '/php/' . $extension, '/');
    }

    public function generate($className, $extension = '', $return = true)
    {
        /** @var Object $class */
        $ref = new ReflectionClass($className);
        $object = new Object($className);

        foreach ($ref->getConstants() as $k => $v) {
            $object->addConstant(Constant::make($k, is_numeric($v) ? $v : '"' . $v . '"'));
        }

        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $rMethod) {
            $method = new Method($rMethod->getName());

            # Add modifiers
            $rMethod->isStatic() && $method->makeStatic();
            $rMethod->isAbstract() && $method->makeAbstract();
            $rMethod->isFinal() && $method->makeFinal();

            # Add params
            foreach ($rMethod->getParameters() as $rParam) {
                $type = 'string';
                if ($paramClass = $rParam->getClass()) {
                    $type = $paramClass->getName();
                }
                $method->addArgument(new Argument($type, $rParam->getName()));
            }
            $object->addMethod($method);
        }

        $baseDir = $this->baseDir($extension);
        $file = $baseDir . "/{$extension}/output/{$className}.php";
        $file = str_replace('//', '//', $file);
        $output = Build::prettyPrinter()->generateCode((new File($file))->setStructure($object));

        if ($return) {
            return $output;
        }
        else {
            $fileName = $baseDir . '/' . str_replace('\\', '/', $className) . '.php';
            $dir = dirname($fileName);
            exec("mkdir -p {$dir}");
            file_put_contents($file, $this->generate($className));

            echo "> class {$className} is exported to `{$baseDir}`.\n";
        }
    }

    public function generateExtension($extension)
    {
        $baseDir = $this->baseDir($extension);
        $printer = Build::prettyPrinter();
        $e = new ReflectionExtension($extension);
        $output = [];

        foreach ($e->getConstants() as $k => $v) {
            $output[] = $printer->generateCode(new Constant($k, $v));
        }

        foreach ($e->getFunctions() as $fn) {
            $function = Method::make($fn->getName());
            $function->removeVisibility();
            foreach ($fn->getParameters() as $p) {
                $type = $p->getType() ? $p->getType()->getName() : null;
                $function->addArgument(Argument::make($type, $p->getName()));
            }

            $output[] = str_replace(['( $'], ['($'], $printer->generateCode($function));
        }

        if ($output) {
            exec("mkdir -p {$baseDir}");
            file_put_contents(
                "{$baseDir}/functions.php",
                "<?php\n\n" . implode("\n\n", $output)
            );
        }

        foreach ($e->getClassNames() as $className) {
            $file = $baseDir . '/' . str_replace('\\', '/', $className) . '.php';
            $dir = dirname($file);
            exec("mkdir -p {$dir}");
            file_put_contents($file, $this->generate($className));
        }

        echo "> {$extension}'s code is exported to `{$baseDir}`.\n";
    }
}
