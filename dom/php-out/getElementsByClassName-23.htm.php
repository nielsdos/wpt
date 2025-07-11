<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-23.htm");
$document = Dom\HTMLDocument::createFromString($content);
;
            test(function()
            { global $document;
                $collection = $document->getElementsByClassName("te xt");
                assert_equals($collection->length, 2);
                assert_equals($collection[0]->parentNode->nodeName, "TR");
                assert_equals($collection[1]->parentNode->nodeName, "BODY");
            }, "multiple defined classes");
        ;