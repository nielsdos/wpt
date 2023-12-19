<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Text-splitText.html");
$document = DOM\HTMLDocument::createFromString($html);
;
test(function() {global $document;
  $text = $document->createTextNode("camembert");
  assert_throws_dom("INDEX_SIZE_ERR", function () use($text) { $text->splitText(10); });
}, "Split text after end of data");
;
test(function() {global $document;
  $text = $document->createTextNode("");
  $new_text = $text->splitText(0);
  assert_equals($text->data, "");
  assert_equals($new_text->data, "");
}, "Split empty text");
;
test(function() {global $document;
  $text = $document->createTextNode("comté");
  $new_text = $text->splitText(0);
  assert_equals($text->data, "");
  assert_equals($new_text->data, "comté");
}, "Split text at beginning");
;
test(function() {global $document;
  $text = $document->createTextNode("comté");
  $new_text = $text->splitText(5);
  assert_equals($text->data, "comté");
  assert_equals($new_text->data, "");
}, "Split text at end");
;
test(function() {global $document;
  $text = $document->createTextNode("comté");
  $new_text = $text->splitText(3);
  assert_equals($text->data, "com");
  assert_equals($new_text->data, "té");
  assert_equals($new_text->parentNode, null);
}, "Split root");
;
test(function() {global $document;
  $parent = $document->createElement('div');
  $text = $document->createTextNode("bleu");
  $parent->appendChild($text);
  $new_text = $text->splitText(2);
  assert_equals($text->data, "bl");
  assert_equals($new_text->data, "eu");
  assert_equals($text->nextSibling, $new_text);
  assert_equals($new_text->parentNode, $parent);
}, "Split child");
;