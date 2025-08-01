<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Node-nodeName-xhtml.xhtml");
$document = DOM\XMLDocument::createFromString($html);
;
test(function() {global $document;
  $HTMLNS = "http://www.w3.org/1999/xhtml";
  $SVGNS = "http://www.w3.org/2000/svg";
  assert_equals($document->createElementNS($HTMLNS, "I")->nodeName, "I");
  assert_equals($document->createElementNS($HTMLNS, "i")->nodeName, "i");
  assert_equals($document->createElementNS($SVGNS, "svg")->nodeName, "svg");
  assert_equals($document->createElementNS($SVGNS, "SVG")->nodeName, "SVG");
  assert_equals($document->createElementNS($HTMLNS, "x:b")->nodeName, "x:b");
}, "For Element nodes, nodeName should return the same as tagName.");
test(function() {global $document;
  assert_equals($document->createTextNode("foo")->nodeName, "#text");
}, "For Text nodes, nodeName should return \"#text\".");
test(function() {global $document;
  assert_equals($document->createProcessingInstruction("foo", "bar")->nodeName,
                "foo");
}, "For ProcessingInstruction nodes, nodeName should return the target.");
test(function() {global $document;
  assert_equals($document->createComment("foo")->nodeName, "#comment");
}, "For Comment nodes, nodeName should return \"#comment\".");
test(function() {global $document;
  assert_equals($document->nodeName, "#document");
}, "For Document nodes, nodeName should return \"#document\".");
test(function() {global $document;
  assert_equals($document->doctype->nodeName, "html");
}, "For DocumentType nodes, nodeName should return the name.");
test(function() {global $document;
  assert_equals($document->createDocumentFragment()->nodeName,
                "#document-fragment");
}, "For DocumentFragment nodes, nodeName should return \"#document-fragment\".");
;