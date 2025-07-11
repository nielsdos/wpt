<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-28.htm");
$document = Dom\HTMLDocument::createFromString($content);
;
            test(function()
            { global $document;
                $collection = $document->getElementsByClassName("te xt");
                assert_equals($collection->length, 3);
                assert_equals($collection[0]->parentNode->nodeName, "BODY");
                assert_equals($collection[1]->parentNode->nodeName, "DIV");
                assert_equals($collection[2]->parentNode->nodeName, "BODY");
            }, "generic element listed");
        ;