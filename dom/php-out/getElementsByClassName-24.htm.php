<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-24.htm");
$document = Dom\HTMLDocument::createFromString($content);
;
            test(function()
            { global $document;
                $collection = $document->getElementsByClassName("ΔЙあ叶葉");
                assert_equals($collection->length, 2);
                assert_equals($collection[0]->parentNode->nodeName, "DIV");
                assert_equals($collection[1]->parentNode->nodeName, "BODY");
            }, "handle unicode chars");
        ;