<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/ParentNode-replaceChildren.html");
$document = DOM\HTMLDocument::createFromString($html);
;
  //preInsertionValidateHierarchy("replaceChildren");
;
  function test_replacechildren($node, $nodeName) {global $document;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $parent->replaceChildren();
      assert_array_equals($parent->childNodes, []);
    }, "{$nodeName}->replaceChildren() without any argument, on a parent having no \$child.");
;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $parent->replaceChildren(null);
      assert_equals($parent->childNodes[0]->textContent, 'null');
    }, "{$nodeName}->replaceChildren() with null as an argument, on a parent having no \$child.");
;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $parent->replaceChildren(undefined);
      assert_equals($parent->childNodes[0]->textContent, 'undefined');
    }, "{$nodeName}->replaceChildren() with undefined as an argument, on a parent having no \$child.");
;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $parent->replaceChildren('text');
      assert_equals($parent->childNodes[0]->textContent, 'text');
    }, "{$nodeName}->replaceChildren() with only text as an argument, on a parent having no \$child.");
;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $x = $document->createElement('x');
      $parent->replaceChildren($x);
      assert_array_equals($parent->childNodes, [$x]);
    }, "{$nodeName}->replaceChildren() with only one element as an argument, on a parent having no \$child.");
;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $child = $document->createElement('test');
      $parent->appendChild($child);
      $parent->replaceChildren();
      assert_array_equals($parent->childNodes, []);
    }, "{$nodeName}->replaceChildren() without any argument, on a parent having a \$child.");
;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $child = $document->createElement('test');
      $parent->appendChild($child);
      $parent->replaceChildren(null);
      assert_equals($parent->childNodes->length, 1);
      assert_equals($parent->childNodes[0]->textContent, 'null');
    }, "{$nodeName}->replaceChildren() with null as an argument, on a parent having a \$child.");
;
    test(function () use ($node,$nodeName) {global $document;
      $parent = $node->cloneNode();
      $x = $document->createElement('x');
      $child = $document->createElement('test');
      $parent->appendChild($child);
      $parent->replaceChildren($x, 'text');
      assert_equals($parent->childNodes->length, 2);
      assert_equals($parent->childNodes[0], $x);
      assert_equals($parent->childNodes[1]->textContent, 'text');
    }, "{$nodeName}->replaceChildren() with one element and text as argument, on a parent having a \$child.");
;
  }
  test_replacechildren($document->createElement('div'), 'Element');
  test_replacechildren($document->createDocumentFragment(), 'DocumentFragment');
;
;