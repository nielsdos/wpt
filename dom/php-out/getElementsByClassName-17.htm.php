<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-17.htm");
$document = Dom\HTMLDocument::createFromString($content);
test(function()  {global $document;
                 assert_array_equals($document->getElementsByClassName("b a"), [$document->body]);
          });
 ;