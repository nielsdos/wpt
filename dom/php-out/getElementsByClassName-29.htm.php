<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-29.htm");
$document = Dom\HTMLDocument::createFromString($content);
;
            test(function()
            { global $document;
                $collection = $document->getElementsByTagName("table")[0]->getElementsByClassName("te xt");
                assert_equals($collection->length, 1);
                assert_equals($collection[0]->parentNode->nodeName, "TR");
            }, "get class from children of element");
        ;