<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/ChildNode-before.html");
$document = DOM\HTMLDocument::createFromString($html);
;
;
function test_before($child, $nodeName, $innerHTML) {global $document;
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $parent->appendChild($child);
        $child->before();
        assert_equals(innerHTML($parent), $innerHTML);
    }, $nodeName . '->before() without any argument->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $parent->appendChild($child);
        $child->before(null);
        $expected = 'null' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with null as an argument->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $parent->appendChild($child);
        $child->before(undefined);
        $expected = 'undefined' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with undefined as an argument->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $parent->appendChild($child);
        $child->before('');
        assert_equals($parent->firstChild->data, '');
    }, $nodeName . '->before() with the empty string as an argument->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $parent->appendChild($child);
        $child->before('text');
        $expected = 'text' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with only text as an argument->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $x = $document->createElement('x');
        $parent->appendChild($child);
        $child->before($x);
        $expected = '<x></x>' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with only one element as an argument->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $x = $document->createElement('x');
        $parent->appendChild($child);
        $child->before($x, 'text');
        $expected = '<x></x>text' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with one element and text as arguments->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $parent->appendChild($child);
        $child->before('text', $child);
        $expected = 'text' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with context object itself as the argument->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $x = $document->createElement('x');
        $parent->appendChild($child);
        $parent->appendChild($x);
        $child->before($x, $child);
        $expected = '<x></x>' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with context object itself and node as the arguments, switching positions->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $x = $document->createElement('x');
        $y = $document->createElement('y');
        $z = $document->createElement('z');
        $parent->appendChild($y);
        $parent->appendChild($child);
        $parent->appendChild($x);
        $child->before($x, $y, $z);
        $expected = '<x></x><y></y><z></z>' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with all siblings of child as arguments->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $x = $document->createElement('x');
        $y = $document->createElement('y');
        $z = $document->createElement('z');
        $parent->appendChild($x);
        $parent->appendChild($y);
        $parent->appendChild($z);
        $parent->appendChild($child);
        $child->before($y, $z);
        $expected = '<x></x><y></y><z></z>' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with some siblings of child as arguments; no changes in tree; viable sibling is first $child->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $v = $document->createElement('v');
        $x = $document->createElement('x');
        $y = $document->createElement('y');
        $z = $document->createElement('z');
        $parent->appendChild($v);
        $parent->appendChild($x);
        $parent->appendChild($y);
        $parent->appendChild($z);
        $parent->appendChild($child);
        $child->before($y, $z);
        $expected = '<v></v><x></x><y></y><z></z>' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with some siblings of child as arguments; no changes in tree->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $x = $document->createElement('x');
        $y = $document->createElement('y');
        $parent->appendChild($x);
        $parent->appendChild($y);
        $parent->appendChild($child);
        $child->before($y, $x);
        $expected = '<y></y><x></x>' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() when pre-insert behaves like prepend->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $parent = $document->createElement('div');
        $x = $document->createElement('x');
        $parent->appendChild($x);
        $parent->appendChild($document->createTextNode('1'));
        $y = $document->createElement('y');
        $parent->appendChild($y);
        $parent->appendChild($child);
        $child->before($x, '2');
        $expected = '1<y></y><x></x>2' . $innerHTML;
        assert_equals(innerHTML($parent), $expected);
    }, $nodeName . '->before() with one sibling of child and text as arguments->');
;
    test(function() use ($child, $nodeName, $innerHTML) {global $document;
        $x = $document->createElement('x');
        $y = $document->createElement('y');
        $x->before($y);
        assert_equals($x->previousSibling, null);
    }, $nodeName . '->before() on a child without any $parent->');
};
;
test_before($document->createComment('test'), 'Comment', '<!--test-->');
test_before($document->createElement('test'), 'Element', '<test></test>');
test_before($document->createTextNode('test'), 'Text', 'test');
;
;