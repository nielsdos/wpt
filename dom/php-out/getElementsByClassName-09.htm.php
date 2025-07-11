<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/getElementsByClassName-09.htm");
$document = DOM\HTMLDocument::createFromString($html);
test(function()  {global $document;
                 assert_array_equals($document->getElementsByClassName("A a"), [$document->documentElement]);
               });
  ;