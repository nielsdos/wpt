<?php
function test($callback, $name) {
    try {
        $callback();
        echo "\033[32;1mPASS\033[0m $name\n";
    } catch (Throwable $e) {
        if (str_contains($e->getMessage(), ", null given")
            || str_contains($e->getMessage(), "Wrong Document")
            || str_contains($e->getMessage(), "Cannot assign null to property DOMNode::\$textContent of type string")) {
            echo "\033[33;1mIGN \033[0m ";
        } else {
            echo "\033[31;1mFAIL\033[0m ";
        }
        echo $name, "\n     => ", $e->getMessage() . "\n";
    }
}

function assert_throws_dom($name, $callback) {
    try {
        $callback();
        throw new Error("Assertion failed: $name not thrown");
    } catch (DOMException $e) {
        if ($e->getCode() !== constant("DOM_".$name)) {
            throw new Error("Assertion failed: $name not thrown (but a different one was: " . $e->getCode() . ")");
        }
    }
}

function assert_equals($a, $b, string $msg = "") {
    if (is_null($a)) $a = "";
    if (is_null($b)) $b = "";
    if ($a instanceof DOMText && is_string($b)) {
        $a = $a->wholeText;
    }
    if ($a !== $b) {
        if (is_string($a) && strlen($a) > 30) {
            $a_short = substr($a, 0, 30) . "...";
        }
        try {
            $msg = "Assertion failed ($msg): \"$a_short\" === \"$b\"";
        } catch (Throwable) {
            $msg = "Assertion failed ($msg): equality";
        }
        var_dump($a, $b);
        throw new Error($msg);
    }
}

function assert_in_array($a, $b) {
    if (!in_array($a, $b)) {
        throw new Error("Assertion failed: $a in " . json_encode($b));
    }
}

function assert_true($a) {
    if ($a !== true) {
        throw new Error("Assertion failed: $a === true");
    }
}

function assert_false($a) {
    if ($a !== false) {
        throw new Error("Assertion failed: $a === false");
    }
}

function attr_is($attr, $v, $ln, $ns, $p, $n) {
    assert_equals($attr->value, $v);
    assert_equals($attr->nodeValue, $v);
    assert_equals($attr->textContent, $v);
    assert_equals($attr->localName, $ln);
    assert_equals($attr->namespaceURI, $ns);
    assert_equals($attr->prefix, $p);
    assert_equals($attr->name, $n);
    assert_equals($attr->nodeName, $n);
    assert_equals($attr->specified, true);
  }

function innerHTML($node) {
    $innerHTML = '';
    foreach ($node->childNodes as $child) {
        $innerHTML .= $node->ownerDocument->saveHTML($child);
    }
    return $innerHTML;
}

function assert_array_equals($array1, $array2) {
    if (count($array1) !== count($array2)) {
        throw new Error("Assertion failed: count(\$array1) (" . count($array1) . ") !== count(\$array2) (" . count($array2) . ")");
    }
    for ($i = 0; $i < count($array1); $i++) {
        if ($array1[$i] !== $array2[$i]) {
            throw new Error("Assertion failed: \$array1[$i] !== \$array2[$i]");
        }
    }
}

function format_value($value) {
    return json_encode($value);
}
