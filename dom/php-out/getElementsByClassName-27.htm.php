<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-27.htm");
$document = Dom\HTMLDocument::createFromString($content);
;
            test(function()
            { global $document;
                $collection = $document->getElementsByClassName("te xt");
;
// var_dump($collection);
// foreach($collection as $node){var_dump($node->tagName);}
                assert_equals($collection->length, 3);
                assert_equals($collection[0]->parentNode->nodeName, "BODY");
                assert_equals($collection[1]->parentNode->nodeName, "DIV");
                assert_equals($collection[2]->parentNode->nodeName, "BODY");
            }, "generic element listed");
        ;