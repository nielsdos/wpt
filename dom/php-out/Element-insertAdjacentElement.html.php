<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Element-insertAdjacentElement.html");
$document = DOM\HTMLDocument::createFromString($html);

$target = $document->getElementById("target");
$target2 = $document->getElementById("target2");

// Not possible because it's an enum
/*test(function() {global $document;
  assert_throws_dom("SYNTAX_ERR", function() {global $document;global $target;
    $target->insertAdjacentElement("test", $document->getElementById("test1"));
  });
;
  assert_throws_dom("SYNTAX_ERR", function() {global $document;global $target2;
    $target2->insertAdjacentElement("test", $document->getElementById("test1"));
  });
}, "Inserting to an invalid location should cause a Syntax Error exception");*/
;
test(function() {global $document;global $target;global $target2;
  $el = $target->insertAdjacentElement(Dom\AdjacentPosition::BeforeBegin, $document->getElementById("test1"));
  assert_equals($target->previousSibling->id, "test1");
  assert_equals($el->id, "test1");
;
  $el = $target2->insertAdjacentElement(Dom\AdjacentPosition::BeforeBegin, $document->getElementById("test1"));
  assert_equals($target2->previousSibling->id, "test1");
  assert_equals($el->id, "test1");
}, "Inserted element should be target element's previous sibling for 'beforebegin' case");
;
test(function() {global $document;global $target;global $target2;
  $el = $target->insertAdjacentElement(Dom\AdjacentPosition::AfterBegin, $document->getElementById("test2"));
  assert_equals($target->firstChild->id, "test2");
  assert_equals($el->id, "test2");
;
  $el = $target2->insertAdjacentElement(Dom\AdjacentPosition::AfterBegin, $document->getElementById("test2"));
  assert_equals($target2->firstChild->id, "test2");
  assert_equals($el->id, "test2");
}, "Inserted element should be target element's first child for 'afterbegin' case");
;
test(function() {global $document;global $target;global $target2;
  $el = $target->insertAdjacentElement(Dom\AdjacentPosition::BeforeEnd, $document->getElementById("test3"));
  assert_equals($target->lastChild->id, "test3");
  assert_equals($el->id, "test3");
;
  $el = $target2->insertAdjacentElement(Dom\AdjacentPosition::BeforeEnd, $document->getElementById("test3"));
  assert_equals($target2->lastChild->id, "test3");
  assert_equals($el->id, "test3");
}, "Inserted element should be target element's last child for 'beforeend' case");
;
test(function() {global $document;global $target;global $target2;
  $el = $target->insertAdjacentElement(Dom\AdjacentPosition::AfterEnd, $document->getElementById("test4"));
  assert_equals($target->nextSibling->id, "test4");
  assert_equals($el->id, "test4");
;
  $el = $target2->insertAdjacentElement(Dom\AdjacentPosition::AfterEnd, $document->getElementById("test4"));
  assert_equals($target2->nextSibling->id, "test4");
  assert_equals($el->id, "test4");
}, "Inserted element should be target element's next sibling for 'afterend' case");
;
test(function() {global $document;
  $docElement = $document->documentElement;
  //$docElement->style->visibility="hidden";
;
  assert_throws_dom("HIERARCHY_REQUEST_ERR", function() use ($docElement) {global $document;
    $el = $docElement->insertAdjacentElement(Dom\AdjacentPosition::BeforeBegin, $document->getElementById("test1"));
    assert_equals($el, null);
  });
;
  $el = $docElement->insertAdjacentElement(Dom\AdjacentPosition::AfterBegin, $document->getElementById("test2"));
  assert_equals($docElement->firstChild->id, "test2");
  assert_equals($el->id, "test2");
;
  $el = $docElement->insertAdjacentElement(Dom\AdjacentPosition::BeforeEnd, $document->getElementById("test3"));
  assert_equals($docElement->lastChild->id, "test3");
  assert_equals($el->id, "test3");
;
  assert_throws_dom("HIERARCHY_REQUEST_ERR", function() use ($docElement) {global $document;
    $el = $docElement->insertAdjacentElement(Dom\AdjacentPosition::AfterEnd, $document->getElementById("test4"));
    assert_equals($el, null);
  });
}, "Adding more than one child to document should cause a HierarchyRequestError exception");
;
;