<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Node-textContent.html");
$document = DOM\HTMLDocument::createFromString($html);
;
// XXX mutation observers?;
// XXX Range gravitation?;
;

  $documents = [
    ["parser", $document],
    ["createDocument", DOM\XMLDocument::createFromString("<test/>")],
    ["createHTMLDocument", DOM\HTMLDocument::createFromString("<!DOCTYPE html><html></html>")],
  ];
  $doctypes = [
    ["parser", $document->doctype],
    //[$document->implementation->createDocumentType("x", "", ""), "script"],
  ];
;
// Getting;
// DocumentFragment, Element:;
test(function() {global $document;
  $element = $document->createElement("div");
  assert_equals($element->textContent, "");
}, "For an empty Element, \$textContent should be the empty string");
;
test(function() {global $document;
  assert_equals($document->createDocumentFragment()->textContent, "");
}, "For an empty DocumentFragment, \$textContent should be the empty string");
;
test(function() {global $document;
  $el = $document->createElement("div");
  $el->appendChild($document->createComment(" abc "));
  $el->appendChild($document->createTextNode("\tDEF\t"));
  $el->appendChild($document->createProcessingInstruction("x", " ghi "));
  assert_equals($el->textContent, "\tDEF\t");
}, "Element with children");
;
test(function() {global $document;
  $el = $document->createElement("div");
  $child = $document->createElement("div");
  $el->appendChild($child);
  $child->appendChild($document->createComment(" abc "));
  $child->appendChild($document->createTextNode("\tDEF\t"));
  $child->appendChild($document->createProcessingInstruction("x", " ghi "));
  assert_equals($el->textContent, "\tDEF\t");
}, "Element with descendants");
;
test(function() {global $document;
  $df = $document->createDocumentFragment();
  $df->appendChild($document->createComment(" abc "));
  $df->appendChild($document->createTextNode("\tDEF\t"));
  $df->appendChild($document->createProcessingInstruction("x", " ghi "));
  assert_equals($df->textContent, "\tDEF\t");
}, "DocumentFragment with children");
;
test(function() {global $document;
  $df = $document->createDocumentFragment();
  $child = $document->createElement("div");
  $df->appendChild($child);
  $child->appendChild($document->createComment(" abc "));
  $child->appendChild($document->createTextNode("\tDEF\t"));
  $child->appendChild($document->createProcessingInstruction("x", " ghi "));
  assert_equals($df->textContent, "\tDEF\t");
}, "DocumentFragment with descendants");
;
// Text, ProcessingInstruction, Comment:;
test(function() {global $document;
  assert_equals($document->createTextNode("")->textContent, "");
}, "For an empty Text, \$textContent should be the empty string");
;
test(function() {global $document;
  assert_equals($document->createProcessingInstruction("x", "")->textContent, "");
}, "For an empty ProcessingInstruction, \$textContent should be the empty string");
;
test(function() {global $document;
  assert_equals($document->createComment("")->textContent, "");
}, "For an empty Comment, \$textContent should be the empty string");
;
test(function() {global $document;
  assert_equals($document->createTextNode("abc")->textContent, "abc");
}, "For a Text with data, \$textContent should be that data");
;
test(function() {global $document;
  assert_equals($document->createProcessingInstruction("x", "abc")->textContent,
                "abc");
}, "For a ProcessingInstruction with data, \$textContent should be that data");
;
test(function() {global $document;
  assert_equals($document->createComment("abc")->textContent, "abc");
}, "For a Comment with data, \$textContent should be that data");
;
// Any other node:;
foreach ($documents as $args) {
  [$creator,$doc]=$args;
  test(function() use ($doc) {global $document;
    assert_equals($doc->textContent, null);
  }, "For Documents created by " . $creator . ", \$textContent should be null");
}

foreach ($doctypes as $args) {
  [$creator,$doctype]=$args;
  test(function() use ($doctype) {global $document;
    assert_equals($doctype->textContent, null);
  }, "For DocumentType created by " . $creator . ", \$textContent should be null");
}
;
// Setting;
// DocumentFragment, Element:;
$testArgs = [
  [null, null],
  [undefined, null],
  ["", null],
  [42, "42"],
  ["abc", "abc"],
  ["<b>xyz<\/b>", "<b>xyz<\/b>"],
  //["d\0e", "d\0e"]
  // XXX unpaired surrogate?;
];
foreach ($testArgs as $aValue) {
  $argument = $aValue[0]; $expectation = $aValue[1];
  $check = function($aElementOrDocumentFragment) use ($expectation) {
    if ($expectation === null) {;
      assert_equals($aElementOrDocumentFragment->textContent, "");
      assert_equals($aElementOrDocumentFragment->firstChild, null);
    } else {;
      assert_equals($aElementOrDocumentFragment->textContent, $expectation);
      assert_equals($aElementOrDocumentFragment->childNodes->length, 1,
                    "Should have one child");
      $firstChild = $aElementOrDocumentFragment->firstChild;
      // assert_true($firstChild instanceof Text, "child should be a Text");
      assert_equals($firstChild->data, $expectation);
    };
  };
;
  test(function() use ($check,$argument) {global $document;
    $el = $document->createElement("div");
    $el->textContent = $argument;
    $check($el);
  }, "Element without children set to " . format_value($argument));
;
  test(function() use ($check,$argument) {global $document;
    $el = $document->createElement("div");
    $text = $el->appendChild($document->createTextNode(""));
    $el->textContent = $argument;
    $check($el);
    assert_equals($text->parentNode, null,
                  "Preexisting Text should have been removed");
  }, "Element with empty text node as child set to " . format_value($argument));
;
  test(function() use ($check,$argument) {global $document;
    $el = $document->createElement("div");
    $el->appendChild($document->createComment(" abc "));
    $el->appendChild($document->createTextNode("\tDEF\t"));
    $el->appendChild($document->createProcessingInstruction("x", " ghi "));
    $el->textContent = $argument;
    $check($el);
  }, "Element with children set to " . format_value($argument));
;
  test(function() use ($check,$argument) {global $document;
    $el = $document->createElement("div");
    $child = $document->createElement("div");
    $el->appendChild($child);
    $child->appendChild($document->createComment(" abc "));
    $child->appendChild($document->createTextNode("\tDEF\t"));
    $child->appendChild($document->createProcessingInstruction("x", " ghi "));
    $el->textContent = $argument;
    $check($el);
    assert_equals($child->childNodes->length, 3,
                  "Should not have changed the internal structure of the removed nodes->");
  }, "Element with descendants set to " . format_value($argument));
;
  test(function() use($check,$argument) {global $document;
    $df = $document->createDocumentFragment();
    $df->textContent = $argument;
    $check($df);
  }, "DocumentFragment without children set to " . format_value($argument));
;
  test(function() use($check,$argument) {global $document;
    $df = $document->createDocumentFragment();
    $text = $df->appendChild($document->createTextNode(""));
    $df->textContent = $argument;
    $check($df);
    assert_equals($text->parentNode, null,
                  "Preexisting Text should have been removed");
  }, "DocumentFragment with empty text node as child set to " . format_value($argument));
;
  test(function() use($check,$argument) {global $document;
    $df = $document->createDocumentFragment();
    $df->appendChild($document->createComment(" abc "));
    $df->appendChild($document->createTextNode("\tDEF\t"));
    $df->appendChild($document->createProcessingInstruction("x", " ghi "));
    $df->textContent = $argument;
    $check($df);
  }, "DocumentFragment with children set to " . format_value($argument));
;
  test(function() use($check,$argument) {global $document;
    $df = $document->createDocumentFragment();
    $child = $document->createElement("div");
    $df->appendChild($child);
    $child->appendChild($document->createComment(" abc "));
    $child->appendChild($document->createTextNode("\tDEF\t"));
    $child->appendChild($document->createProcessingInstruction("x", " ghi "));
    $df->textContent = $argument;
    $check($df);
    assert_equals($child->childNodes->length, 3,
                  "Should not have changed the internal structure of the removed nodes->");
  }, "DocumentFragment with descendants set to " . format_value($argument));
}
;
// Text, ProcessingInstruction, Comment:;
test(function() {global $document;
  $text = $document->createTextNode("abc");
  $text->textContent = "def";
  assert_equals($text->textContent, "def");
  assert_equals($text->data, "def");
}, "For a Text, \$textContent should set the data");
;
test(function() {global $document;
  $pi = $document->createProcessingInstruction("x", "abc");
  $pi->textContent = "def";
  assert_equals($pi->textContent, "def");
  assert_equals($pi->data, "def");
  assert_equals($pi->target, "x");
}, "For a ProcessingInstruction, \$textContent should set the data");
;
test(function() {global $document;
  $comment = $document->createComment("abc");
  $comment->textContent = "def";
  assert_equals($comment->textContent, "def");
  assert_equals($comment->data, "def");
}, "For a Comment, \$textContent should set the data");
;
// Any other node:;
foreach ($documents as $argument) {
  $doc = $argument[1]; $creator = $argument[0];
  test(function() use($doc) {global $document;
    $root = $doc->documentElement;
    $doc->textContent = "a";
    assert_equals($doc->textContent, null);
    assert_equals($doc->documentElement, $root);
  }, "For Documents created by " . $creator . ", setting textContent should do nothing");
}
;
foreach ($doctypes as $argument) {
  $doctype = $argument[1]; $creator = $argument[0];
  test(function() use ($doctype) {global $document;
    $props = (object)[
      'name' => $doctype->name,
      'publicId' => $doctype->publicId,
      'systemId' => $doctype->systemId,
    ];
    $doctype->textContent = "b";
    assert_equals($doctype->textContent, null);
    assert_equals($doctype->name, $props->name, "name should not change");
    assert_equals($doctype->publicId, $props->publicId, "publicId should not change");
    assert_equals($doctype->systemId, $props->systemId, "systemId should not change");
  }, "For DocumentType created by " . $creator . ", setting textContent should do nothing");
}
