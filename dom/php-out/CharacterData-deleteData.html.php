<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/CharacterData-deleteData.html");
$document = DOM\HTMLDocument::createFromString($html);
;
function testNode($create, $type) {global $document;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    assert_throws_dom("INDEX_SIZE_ERR", function() use($node) { $node->deleteData(5, 10); });
    assert_throws_dom("INDEX_SIZE_ERR", function() use($node) { $node->deleteData(5, 0); });
    assert_throws_dom("INDEX_SIZE_ERR", function() use($node) { $node->deleteData(-1, 10); });
    assert_throws_dom("INDEX_SIZE_ERR", function() use($node) { $node->deleteData(-1, 0); });
  }, $type . "->deleteData() out of bounds");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->deleteData(0, 2);
    assert_equals($node->data, "st");
  }, $type . "->deleteData() at the start");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->deleteData(2, 10);
    assert_equals($node->data, "te");
  }, $type . "->deleteData() at the end");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->deleteData(1, 1);
    assert_equals($node->data, "tst");
  }, $type . "->deleteData() in the middle");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->deleteData(2, 0);
    assert_equals($node->data, "test");
;
    $node->deleteData(0, 0);
    assert_equals($node->data, "test");
  }, $type . "->deleteData() with zero count");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->deleteData(2, -1);
    assert_equals($node->data, "te");
  }, $type . "->deleteData() with small negative count");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->deleteData(1, -0x100000000 + 2);
    assert_equals($node->data, "tt");
  }, $type . "->deleteData() with large negative count");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    $node->data = "This is the character data test, append more 資料，更多測試資料";
;
    $node->deleteData(40, 5);
    assert_equals($node->data, "This is the character data test, append 資料，更多測試資料");
    $node->deleteData(45, 2);
    assert_equals($node->data, "This is the character data test, append 資料，更多資料");
  }, $type . "->deleteData() with non-ascii data");
;
  test(function() use ($create, $type) {global $document;
    $node = $create();
    assert_equals($node->data, "test");
;
    $node->data = "🌠 test 🌠 TEST";
;
    $node->deleteData(5, 8);  // DEVIATION: UTF8!!!
    assert_equals($node->data, "🌠 tes");
  }, $type . "->deleteData() with non-BMP data");
};
;
testNode(function() { global $document; return $document->createTextNode("test"); }, "Text");
testNode(function() { global $document; return $document->createComment("test"); }, "Comment");
;