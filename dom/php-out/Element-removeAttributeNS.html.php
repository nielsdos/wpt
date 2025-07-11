<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Element-removeAttributeNS.html");
$document = DOM\HTMLDocument::createFromString($html);
;
$XML = "http://www.w3.org/XML/1998/namespace";
;
test(function() {global $document;global $XML;
  $el = $document->createElement("foo");
  $el->setAttributeNS($XML, "a:bb", "pass");
  attr_is($el->attributes[0], "pass", "bb", $XML, "a", "a:bb");
  $el->removeAttributeNS($XML, "a:bb");
  assert_equals($el->attributes->length, 1);
  attr_is($el->attributes[0], "pass", "bb", $XML, "a", "a:bb");
}, "removeAttributeNS should take a local name.");
;