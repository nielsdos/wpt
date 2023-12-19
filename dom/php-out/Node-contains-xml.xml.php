<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Node-contains-xml.xml");
$document = DOM\XMLDocument::createFromString($html);
/*
test(function() {global $document;
  assert_throws_js(TypeError, function() {global $document;;
    $document->contains();
  });
  assert_throws_js(TypeError, function() {global $document;;
    $document->contains(9);
  });
}, "Should throw TypeError if the arguments are wrong->");*/
;
test(function() {global $document;
  assert_equals($document->contains(null), false, "Document shouldn't contain null->");
}, "contains(null) should be false");
;
test(function() {global $document;
  assert_equals($document->contains($document), true, "Document should contain itself!");
  assert_equals($document->contains($document->createElement("foo")), false, "Document shouldn't contain element which is't in the document");
  assert_equals($document->contains($document->createTextNode("foo")), false, "Document shouldn't contain text node which is't in the document");
}, "\$document->contains");
;
test(function() {global $document;
  $tb = $document->getElementById("testbutton");
  assert_equals($tb->contains($tb), true, "Element should contain itself->");
  assert_equals($document->contains($tb), true, "Document should contain element in it!");
  assert_equals($document->documentElement->contains($tb), true, "Element should contain element in it!");
}, "contains with a button");
;
test(function() {global $document;
  $link = $document->getElementById("link");
  $text = $link->firstChild;
  assert_equals($document->contains($text), true, "Document should contain a text node in it->");
  assert_equals($link->contains($text), true, "Element should contain a text node in it->");
  assert_equals($text->contains($text), true, "Text node should contain itself->");
  assert_equals($text->contains($link), false, "text node shouldn't contain its parent->");
}, "contains with a text node");
;
test(function() {global $document;
  $pi = $document->createProcessingInstruction("adf", "asd");
  assert_equals($pi->contains($document), false, "Processing instruction shouldn't contain document");
  assert_equals($document->contains($pi), false, "Document shouldn't contain newly created processing instruction");
  $document->documentElement->appendChild($pi);
  assert_equals($document->contains($pi), true, "Document should contain processing instruction");
}, "contains with a processing instruction");
;
/*test(function() {global $document;
  if ("createContextualFragment" in $document->createRange()) {;
    $df = $document->createRange()->createContextualFragment("<div>foo</div>");
    assert_equals($df->contains($df->firstChild), true, "Document fragment should contain its child");
    assert_equals($df->contains($df->firstChil$d->firstChild), true,;
       "Document fragment should contain its descendant");
    assert_equals($df->contains($df), true, "Document fragment should contain itself->");
  };
}, "contains with a document fragment");
;*/
test(function() {global $document;
  $d = DOM\HTMLDocument::createEmpty();
  assert_equals($document->contains($d), false,
     "Document shouldn't contain another \$document->");
  assert_equals($document->contains($d->createElement("div")), false,
     "Document shouldn't contain an element from another \$document->");
  assert_equals($document->contains($d->documentElement), false,
     "Document shouldn't contain an element from another \$document->");
}, "contains with another document");
