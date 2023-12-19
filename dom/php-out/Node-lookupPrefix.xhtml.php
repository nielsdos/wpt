<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Node-lookupPrefix.xhtml");
$document = DOM\XMLDocument::createFromString($html);
;

error_reporting(E_ALL&~E_DEPRECATED); // for null -> string conversion

function lookupPrefix($node, $ns, $prefix) {global $document;
  test(function() use ($node, $ns, $prefix) {global $document;
    assert_equals($node->lookupPrefix($ns), $prefix);
  }, "lookupPrefix($ns, $prefix)");
};
$x = $document->getElementsByTagName("x")[0];
lookupPrefix($document, "test", "x"); // XXX add test for when there is no documentElement;
lookupPrefix($document, null, null);
lookupPrefix($x, "http://www->w3->org/1999/xhtml", null);
lookupPrefix($x, "something", null);
lookupPrefix($x, null, null);
lookupPrefix($x, "test", "t");
lookupPrefix($x->parentNode, "test", "s");
lookupPrefix($x->firstChild, "test", "t");
lookupPrefix($x->childNodes[1], "test", "t");
lookupPrefix($x->childNodes[2], "test", "t");
lookupPrefix($x->lastChild, "test", "t");
$x->parentNode->removeChild($x);
;