<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/getElementsByClassName-02.htm");
$document = DOM\HTMLDocument::createFromString($html);
 test(function()  {global $document;assert_array_equals($document->getElementsByClassName("a\n"), [$document->documentElement, $document->body]);}) ;