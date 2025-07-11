<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-22.htm");
$document = Dom\HTMLDocument::createFromString($content);
;
            test(function()
            { global $document;
                $collection = $document->getElementsByClassName("text");
                assert_equals($collection->length, 4);
                $boldText = $document->getElementsByTagName("b")[0];
                // $document->getElementsByTagName("table")[0]->tBodies[0]->rows[0]->cells[0]->appendChild($boldText);
                $document->querySelector('table > tbody > tr > td')->appendChild($boldText);
;
                assert_equals($collection->length, 4);
                assert_equals($collection[0]->parentNode->nodeName, "DIV");
                assert_equals($collection[1]->parentNode->nodeName, "TABLE");
                assert_equals($collection[2]->parentNode->nodeName, "TD");
                assert_equals($collection[3]->parentNode->nodeName, "TR");
            }, "move item in collection order");
        ;