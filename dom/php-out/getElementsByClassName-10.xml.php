<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/getElementsByClassName-10.xml");
$document = DOM\XMLDocument::createFromString($html);
test(function()  {global $document;
    throw new Error('todo');
                 assert_array_equals($document->getElementsByClassName("a"),
                                     $document->getElementById("tests")->children);
               });
  ;