<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/CharacterData-data.html");
$document = DOM\HTMLDocument::createFromString($html);
;
function testNode($create, $type) {global $document;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
    assert_equals($node->length, 4);
  }, $type . "->data initial value");
;
  /*test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = null;
    assert_equals($node->data, "");
    assert_equals($node->length, 0);
  }, $type . "->data = null");*/
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = undefined;
    assert_equals($node->data, "undefined");
    assert_equals($node->length, 9);
  }, $type . "->data = undefined");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = 0;
    assert_equals($node->data, "0");
    assert_equals($node->length, 1);
  }, $type . "->data = 0");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = "";
    assert_equals($node->data, "");
    assert_equals($node->length, 0);
  }, $type . "->data = ''");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = "--";
    assert_equals($node->data, "--");
    assert_equals($node->length, 2);
  }, $type . "->data = '--'");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = "資料";
    assert_equals($node->data, "資料");
    assert_equals($node->length, 2);
  }, $type . "->data = '資料'");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = "🌠 test 🌠 TEST";
    assert_equals($node->data, "🌠 test 🌠 TEST");
    assert_equals($node->length, 13);  // DIVERGENCE: in UTF-8 this is 13 codepoints, not 15
  }, $type . "->data = '🌠 test 🌠 TEST'");
};
;
testNode(function() { global $document;return $document->createTextNode("test"); }, "Text");
testNode(function() { global $document;return $document->createComment("test"); }, "Comment");
;