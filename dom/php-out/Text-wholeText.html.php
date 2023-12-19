<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Text-wholeText.html");
$document = DOM\HTMLDocument::createFromString($html);

test(function() {
  global $document;
  $parent = $document->createElement("div");
;
  $t1 = $document->createTextNode("a");
  $t2 = $document->createTextNode("b");
  $t3 = $document->createTextNode("c");
;
  assert_equals($t1->wholeText, $t1->textContent);
;
  $parent->appendChild($t1);
;
  assert_equals($t1->wholeText, $t1->textContent);
;
  $parent->appendChild($t2);
;
  assert_equals($t1->wholeText, $t1->textContent . $t2->textContent);
  assert_equals($t2->wholeText, $t1->textContent . $t2->textContent);
;
  $parent->appendChild($t3);
;
  assert_equals($t1->wholeText, $t1->textContent . $t2->textContent . $t3->textContent);
  assert_equals($t2->wholeText, $t1->textContent . $t2->textContent . $t3->textContent);
  assert_equals($t3->wholeText, $t1->textContent . $t2->textContent . $t3->textContent);
;
  $a = $document->createElement("a");
  $a->textContent = "I'm an Anchor";
  $parent->insertBefore($a, $t3);
;
  $span = $document->createElement("span");
  $span->textContent = "I'm a Span";
  $parent->appendChild($document->createElement("span"));
;
  assert_equals($t1->wholeText, $t1->textContent . $t2->textContent);
  assert_equals($t2->wholeText, $t1->textContent . $t2->textContent);
  assert_equals($t3->wholeText, $t3->textContent);
}, "wholeText returns text of all Text nodes logically adjacent to the node, in document order->");
;