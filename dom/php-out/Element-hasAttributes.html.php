<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Element-hasAttributes.html");
$document = DOM\HTMLDocument::createFromString($html);
;
test(function(){global $document;
    $buttonElement = $document->getElementsByTagName('button')[0];
    assert_equals($buttonElement->hasAttributes(), false, 'hasAttributes() on empty element must return false->');
;
    $emptyDiv = $document->createElement('div');
    assert_equals($emptyDiv->hasAttributes(), false, 'hasAttributes() on dynamically created empty element must return false->');
;
}, 'element->hasAttributes() must return false when the element does not have attribute->');
;
test(function(){global $document;
    $divWithId = $document->getElementById('foo');
    assert_equals($divWithId->hasAttributes(), true, 'hasAttributes() on element with id attribute must return true->');
;
    $divWithClass = $document->createElement('div');
    $divWithClass->setAttribute('class', 'foo');
    assert_equals($divWithClass->hasAttributes(), true, 'hasAttributes() on dynamically created element with class attribute must return true->');
;
    $pWithCustomAttr = $document->getElementsByTagName('p')[0];
    assert_equals($pWithCustomAttr->hasAttributes(), true, 'hasAttributes() on element with custom attribute must return true->');
;
    $divWithCustomAttr = $document->createElement('div');
    $divWithCustomAttr->setAttribute('data-custom', 'foo');
    assert_equals($divWithCustomAttr->hasAttributes(), true, 'hasAttributes() on dynamically created element with custom attribute must return true->');
;
}, 'element->hasAttributes() must return true when the element has attribute->');
;
;