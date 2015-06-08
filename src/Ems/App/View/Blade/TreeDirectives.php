<?php namespace Ems\App\View\Blade;


use Illuminate\View\Compilers\BladeCompiler as Compiler;

class TreeDirectives
{

    public $names = [
        'tree' => 'tree'
    ];

    public function register(Compiler $blade)
    {
        foreach ($this->names as $method=>$name) {
            $this->{"register$method"}($blade, $name);
        }
    }

    protected function registerTree(Compiler $blade, $name)
    {
        $blade->extend(function($view, $compiler) use ($name) {

            $pattern = $compiler->createMatcher($name);

            $code = <<<'EOD'
                    $1<?php
                    if (isset($form)) {
                        echo $form;
                    } else{
                        $passed = array$2;
                        if (count($passed) == 2) {
                            $m = $passed[0];
                            $res = $passed[1];
                        }
                        if (count($passed) == 1) {
                            $m = $passed[0];
                            $res = isset($resource) ? $resource : null;
                        }
                        if (count($passed) == 0) {
                            $m = isset($model) ? $model : null;
                            $res = isset($resource) ? $resource : null;
                        }
                        echo Resource::form($m, $res);
                    }
                ?>
EOD;

            return preg_replace($pattern, $code, $view);
        });
    }

}