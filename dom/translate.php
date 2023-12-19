<?php

//$files = glob("**/*.js");
//var_dump($files);

function translate_file(string $filename) {
    $contents = file_get_contents($filename);
    $dom = DOM\HTMLDocument::createFromString($contents);
    foreach ($dom->getElementsByTagName("script") as $script) {
        if (!$script->hasAttribute("src")) {
            $script = $script->textContent;
        }
    }

    $php = $script;

    $variables = ['document' => true];
    foreach (['var', 'const', 'let'] as $var_kw) {
        $php = preg_replace_callback("/$var_kw ([a-zA-Z0-9_]+)/", function ($matches) use (&$variables) {
            $variables[$matches[1]] = true;
            return "\$$matches[1]";
        }, $php);
    }
    
    var_dump($variables);
    
    $arguments = [];
    $php = preg_replace_callback("/function ([a-zA-Z0-9_]+)\((.*)/", function ($matches) use (&$variables, &$arguments) {
        $params = $matches[2];
        foreach (explode(", ", $params) as $arg) {
            if (strrpos($arg, ")") !== false) {
                $arg = substr($arg, 0, strrpos($arg, ")"));
            }
            $variables[$arg] = true;
            $arguments[$arg] = true;
        }
        $params = str_replace(", ", ", \$", $params);
        return "function " . $matches[1] . "(\$" . $params . "global \$document;";
    }, $php);

    var_dump($arguments);

    $arguments_as_vars = implode(', ', array_map(fn($s) => "\$$s", array_keys($arguments)));
    $php = str_replace("test(function() {", "test(function() use ($arguments_as_vars) {global \$document;", $php);

    $lines = [];
    foreach (explode("\n", $php) as $line) {
        if (!str_ends_with($line, ";")) {
            $line .= ";";
        }
        $lines[] = $line;
    }
    $php = implode("\n", $lines);

    foreach ($variables as $variable => $_) {
        $php = str_replace("($variable", "(\$$variable", $php);
        $php = str_replace("$variable.", "\$$variable.", $php);
        $php = str_replace(", $variable", ", \$$variable", $php);
        $php = str_replace("+ $variable", "+ \$$variable", $php);
        $php = str_replace("$variable + ", "\$$variable + ", $php);
    }

    $php = str_replace('$$', '$', $php);
    $php = str_replace('\n', ';\n', $php);
    $php = str_replace(".", "->", $php);
    $php = str_replace("`", "\"", $php);
    $php = str_replace(" + '", " . '", $php);
    $php = str_replace(" + '", " . \"", $php);
    $php = str_replace("' + ", "' . ", $php);
    $php = str_replace("' + ", "\" . ", $php);
    $php = str_replace("create();", "\$create();", $php);
    $php = str_replace("function() {", "function() {global \$document;", $php);
    $php = preg_replace("/(\\$[a-zA-Z0-9_]+)->innerHTML/", "innerHTML($1)", $php);

    $preamble = "<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';\n";
    $preamble .= "\$html = file_get_contents(__DIR__.\"/../$filename\");\n";
    $preamble .= '$document = DOM\HTMLDocument::createFromString($html);' . "\n";

    @mkdir("php-out");
    $filename = basename($filename);
    file_put_contents("php-out/$filename.php", $preamble.$php);
}

var_dump($_SERVER['argv']);

if (count($_SERVER['argv']) !== 2) {
    die("Usage: php translate.php <filename>\n");
}

if (!file_exists($_SERVER['argv'][1])) {
    die("File not found: " . $_SERVER['argv'][1] . "\n");
}

translate_file($_SERVER['argv'][1]);
