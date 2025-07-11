<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-14.htm");
$document = Dom\HTMLDocument::createFromString($content, LIBXML_NOERROR);
;
test(function()  {global $document;
  assert_array_equals($document->getElementsByClassName("A a"),
                      [$document->documentElement, $document->body]);
});
;
test(function()  {global $document;
  assert_array_equals($document->getElementsByClassName("\u{212a}"),
                      [$document->getElementById("kelvin")]);
}, 'Unicode-case should be sensitive even in quirks mode');
  ;