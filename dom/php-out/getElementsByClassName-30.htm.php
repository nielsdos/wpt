<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$content = file_get_contents(__DIR__."/../nodes/getElementsByClassName-30.htm");
$document = Dom\HTMLDocument::createFromString($content, LIBXML_NOERROR);
;
    test(function ()
          {global $document;
           $arrElements = [
                "HEAD",
                "TITLE",
                "META",
                "LINK",
                "BASE",
                "SCRIPT",
                "STYLE",
                "BODY",
                "A",
                "ABBR",
                "ACRONYM",
                "ADDRESS",
                "APPLET",
                "B",
                "BDO",
                "BIG",
                "BLOCKQUOTE",
                "BR",
                "BUTTON",
                "CENTER",
                "CITE",
                "CODE",
                "DEL",
                "DFN",
                "DIR",
                "LI",
                "DIV",
                "DL",
                "DT",
                "DD",
                "EM",
                "FONT",
                "FORM",
                "LABEL",
                "FIELDSET",
                "LEGEND",
                "H1",
                "HR",
                "I",
                "IFRAME",
                "IMG",
                "INPUT",
                "INS",
                "KBD",
                "MAP",
                "AREA",
                "MENU",
                "NOSCRIPT",
                "OBJECT",
                "PARAM",
                "OL",
                "P",
                "PRE",
                "Q",
                "S",
                "SAMP",
                "SELECT",
                "OPTGROUP",
                "OPTION",
                "SMALL",
                "SPAN",
                "STRIKE",
                "STRONG",
                "SUB",
                "SUP",
                "TABLE",
                "CAPTION",
                "COL",
                "COLGROUP",
                "THEAD",
                "TH",
                "TBODY",
                "TR",
                "TD",
                "TFOOT",
                "TEXTAREA",
                "TT",
                "U",
                "UL",
                "VAR"];
;
                $collection = $document->getElementsByClassName("foo");
                for ($x = 0; $x < $collection->length; $x++)
                {
                    assert_equals($collection[$x]->nodeName, $arrElements[$x]);
                }
}, "big element listing");
        ;