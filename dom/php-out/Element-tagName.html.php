<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Element-tagName.html");
$document = DOM\HTMLDocument::createFromString($html);
;
test(function() {global $document;
  $HTMLNS = "http://www.w3.org/1999/xhtml";
  assert_equals($document->createElementNS($HTMLNS, "I")->tagName, "I");
  assert_equals($document->createElementNS($HTMLNS, "i")->tagName, "I");
  assert_equals($document->createElementNS($HTMLNS, "x:b")->tagName, "X:B");
}, "tagName should upper-case for HTML elements in HTML documents.");
;
test(function() {global $document;
  $SVGNS = "http://www.w3.org/2000/svg";
  assert_equals($document->createElementNS($SVGNS, "svg")->tagName, "svg");
  assert_equals($document->createElementNS($SVGNS, "SVG")->tagName, "SVG");
  assert_equals($document->createElementNS($SVGNS, "s:svg")->tagName, "s:svg");
  assert_equals($document->createElementNS($SVGNS, "s:SVG")->tagName, "s:SVG");
;
  assert_equals($document->createElementNS($SVGNS, "textPath")->tagName, "textPath");
}, "tagName should not upper-case for SVG elements in HTML documents.");
;
test(function() {global $document;
  $el2 = $document->createElementNS("http://example->com/", "mixedCase");
  assert_equals($el2->tagName, "mixedCase");
}, "tagName should not upper-case for other non-HTML namespaces");
;
/*test(function() {global $document;
  if ("DOMParser" in window) {;
    $xmlel = new DOMParser();
      ->parseFromString('<div xmlns="http://www.w3.org/1999/xhtml">Test</div>', 'text/xml');
      ->documentElement;
    assert_equals($xmlel->tagName, "div", "tagName should be lowercase in XML");
    $htmlel = $document->importNode($xmlel, true);
    assert_equals($htmlel->tagName, "DIV", "tagName should be uppercase in HTML");
  };
}, "tagName should be updated when changing ownerDocument");*/
;
test(function() {global $document;
  $xml = DOM\XMLDocument::createEmpty();
  $xmlel = $xml->createElementNS("http://www.w3.org/1999/xhtml", "div");
  assert_equals($xmlel->tagName, "div", "tagName should be lowercase in XML");
  $htmlel = $document->importNode($xmlel, true);
  assert_equals($htmlel->tagName, "DIV", "tagName should be uppercase in HTML");
}, "tagName should be updated when changing ownerDocument (createDocument without prefix)");
;
test(function() {global $document;
  $xml = DOM\XMLDocument::createEmpty();
  $xmlel = $xml->createElementNS("http://www.w3.org/1999/xhtml", "foo:div");
  $htmlel = $document->importNode($xmlel, true);
  assert_equals($htmlel->tagName, "FOO:DIV", "tagName should be uppercase in HTML");
}, "tagName should be updated when changing ownerDocument (createDocument with prefix)");
;