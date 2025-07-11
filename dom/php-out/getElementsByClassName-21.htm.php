<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-21.htm");
$document = Dom\HTMLDocument::createFromString($content);
;
            test(function()
            { global $document;
                $collection = $document->getElementsByClassName("text1");
                assert_equals($collection->length, 1);
                $document->querySelectorAll('table > tbody > tr')[0]->remove();
                assert_equals($collection->length, 0);
            }, "delete element from collection");
        ;