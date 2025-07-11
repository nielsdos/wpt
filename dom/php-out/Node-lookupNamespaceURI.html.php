<?php define('undefined', 'undefined');require __DIR__.'/../driver.inc.php';
$html = file_get_contents(__DIR__."/../nodes/Node-lookupNamespaceURI.html");
$document = DOM\HTMLDocument::createFromString($html);

error_reporting(E_ALL&~E_DEPRECATED); // for null -> string conversion

;
function lookupNamespaceURI($node, $prefix, $expected, $name) {global $document;
  test(function() use ($node, $prefix, $expected, $name) {global $document;
    assert_equals($node->lookupNamespaceURI($prefix), $expected);
  }, $name);
};
;
function isDefaultNamespace($node, $namespace, $expected, $name) {global $document;
  test(function() use ($node, $expected, $name, $namespace) {global $document;
    assert_equals($node->isDefaultNamespace($namespace), $expected);
  }, $name);
};
;
;
$frag = $document->createDocumentFragment();
lookupNamespaceURI($frag, null, null, 'DocumentFragment should have null namespace, $prefix null');
lookupNamespaceURI($frag, '', null, 'DocumentFragment should have null namespace, $prefix ""');
lookupNamespaceURI($frag, 'foo', null, 'DocumentFragment should have null namespace, $prefix "foo"');
lookupNamespaceURI($frag, 'xml', null, 'DocumentFragment should have null namespace, $prefix "xml"');
lookupNamespaceURI($frag, 'xmlns', null, 'DocumentFragment should have null namespace, $prefix "xmlns"');
isDefaultNamespace($frag, null, true, 'DocumentFragment is in default namespace, $prefix null');
isDefaultNamespace($frag, '', true, 'DocumentFragment is in default namespace, $prefix ""');
isDefaultNamespace($frag, 'foo', false, 'DocumentFragment is in default namespace, $prefix "foo"');
isDefaultNamespace($frag, 'xmlns', false, 'DocumentFragment is in default namespace, $prefix "xmlns"');
;
$docType = $document->doctype;
lookupNamespaceURI($docType, null, null, 'DocumentType should have null namespace, $prefix null');
lookupNamespaceURI($docType, '', null, 'DocumentType should have null namespace, $prefix ""');
lookupNamespaceURI($docType, 'foo', null, 'DocumentType should have null namespace, $prefix "foo"');
lookupNamespaceURI($docType, 'xml', null, 'DocumentType should have null namespace, $prefix "xml"');
lookupNamespaceURI($docType, 'xmlns', null, 'DocumentType should have null namespace, $prefix "xmlns"');
isDefaultNamespace($docType, null, true, 'DocumentType is in default namespace, $prefix null');
isDefaultNamespace($docType, '', true, 'DocumentType is in default namespace, $prefix ""');
isDefaultNamespace($docType, 'foo', false, 'DocumentType is in default namespace, $prefix "foo"');
isDefaultNamespace($docType, 'xmlns', false, 'DocumentType is in default namespace, $prefix "xmlns"');
;
$fooElem = $document->createElementNS('fooNamespace', 'prefix:elem');
$fooElem->setAttribute('bar', 'value');
$XMLNS_NS = 'http://www.w3.org/2000/xmlns/';
$XML_NS =  'http://www.w3.org/XML/1998/namespace';
lookupNamespaceURI($fooElem, null, null, 'Element should have null namespace, $prefix null');
lookupNamespaceURI($fooElem, '', null, 'Element should have null namespace, $prefix ""');
lookupNamespaceURI($fooElem, 'fooNamespace', null, 'Element should not have namespace matching prefix with namespaceURI value');
lookupNamespaceURI($fooElem, 'xml', $XML_NS, 'Element should have XML namespace');
lookupNamespaceURI($fooElem, 'xmlns', $XMLNS_NS, 'Element should have XMLNS namespace');
lookupNamespaceURI($fooElem, 'prefix', 'fooNamespace', 'Element has namespace URI matching prefix');
isDefaultNamespace($fooElem, null, true, 'Empty namespace is not default, $prefix null');
isDefaultNamespace($fooElem, '', true, 'Empty namespace is not default, $prefix ""');
isDefaultNamespace($fooElem, 'fooNamespace', false, 'fooNamespace is not default');
isDefaultNamespace($fooElem, $XMLNS_NS, false, 'xmlns namespace is not default');
;
$fooElem->setAttributeNS($XMLNS_NS, 'xmlns:bar', 'barURI');
$fooElem->setAttributeNS($XMLNS_NS, 'xmlns', 'bazURI');
;
lookupNamespaceURI($fooElem, null, 'bazURI', 'Element should have baz namespace, $prefix null');
lookupNamespaceURI($fooElem, '', 'bazURI', 'Element should have baz namespace, $prefix ""');
lookupNamespaceURI($fooElem, 'xmlns', $XMLNS_NS, 'Element should have namespace with xmlns prefix');
lookupNamespaceURI($fooElem, 'bar', 'barURI', 'Element has bar namespace');
;
isDefaultNamespace($fooElem, null, false, 'Empty namespace is not default on fooElem, $prefix null');
isDefaultNamespace($fooElem, '', false, 'Empty namespace is not default on fooElem, $prefix ""');
isDefaultNamespace($fooElem, 'barURI', false, 'bar namespace is not default');
isDefaultNamespace($fooElem, 'bazURI', true, 'baz namespace is default');
;
$comment = $document->createComment('comment');
$fooElem->appendChild($comment);
;
lookupNamespaceURI($comment, null, 'bazURI', 'Comment should inherit baz namespace');
lookupNamespaceURI($comment, '', 'bazURI', 'Comment should inherit  baz namespace');
lookupNamespaceURI($comment, 'prefix', 'fooNamespace', 'Comment should inherit namespace URI matching prefix');
lookupNamespaceURI($comment, 'bar', 'barURI', 'Comment should inherit bar namespace');
;
isDefaultNamespace($comment, null, false, 'For comment, empty namespace is not default, $prefix null');
isDefaultNamespace($comment, '', false, 'For comment, empty namespace is not default, $prefix ""');
isDefaultNamespace($comment, 'fooNamespace', false, 'For comment, fooNamespace is not default');
isDefaultNamespace($comment, $XMLNS_NS, false, 'For comment, xmlns namespace is not default');
isDefaultNamespace($comment, 'barURI', false, 'For comment, inherited bar namespace is not default');
isDefaultNamespace($comment, 'bazURI', true, 'For comment, inherited baz namespace is default');
;
$fooChild = $document->createElementNS('childNamespace', 'childElem');
$fooElem->appendChild($fooChild);
;
lookupNamespaceURI($fooChild, null, 'childNamespace', 'Child element should inherit baz namespace');
lookupNamespaceURI($fooChild, '', 'childNamespace', 'Child element should have null namespace');
lookupNamespaceURI($fooChild, 'xmlns', $XMLNS_NS, 'Child element should have XMLNS namespace');
lookupNamespaceURI($fooChild, 'prefix', 'fooNamespace', 'Child element has namespace URI matching prefix');
;
isDefaultNamespace($fooChild, null, false, 'Empty namespace is not default for child, $prefix null');
isDefaultNamespace($fooChild, '', false, 'Empty namespace is not default for child, $prefix ""');
isDefaultNamespace($fooChild, 'fooNamespace', false, 'fooNamespace is not default for child');
isDefaultNamespace($fooChild, $XMLNS_NS, false, 'xmlns namespace is not default for child');
isDefaultNamespace($fooChild, 'barURI', false, 'bar namespace is not default for child');
isDefaultNamespace($fooChild, 'bazURI', false, 'baz namespace is default for child');
isDefaultNamespace($fooChild, 'childNamespace', true, 'childNamespace is default for child');
;
$document->documentElement->setAttributeNS($XMLNS_NS, 'xmlns:bar', 'barURI');
$document->documentElement->setAttributeNS($XMLNS_NS, 'xmlns', 'bazURI');
;
lookupNamespaceURI($document, null, 'http://www.w3.org/1999/xhtml', 'Document should have xhtml namespace, $prefix null');
lookupNamespaceURI($document, '', 'http://www.w3.org/1999/xhtml', 'Document should have xhtml namespace, $prefix ""');
lookupNamespaceURI($document, 'prefix', null, 'Document has no namespace URI matching prefix');
lookupNamespaceURI($document, 'bar', 'barURI', 'Document has bar namespace');
;
isDefaultNamespace($document, null, false, 'For document, empty namespace is not default, $prefix null');
isDefaultNamespace($document, '', false, 'For document, empty namespace is not default, $prefix ""');
isDefaultNamespace($document, 'fooNamespace', false, 'For document, fooNamespace is not default');
isDefaultNamespace($document, $XMLNS_NS, false, 'For document, xmlns namespace is not default');
isDefaultNamespace($document, 'barURI', false, 'For document, bar namespace is not default');
isDefaultNamespace($document, 'bazURI', false, 'For document, baz namespace is not default');
isDefaultNamespace($document, 'http://www.w3.org/1999/xhtml', true, 'For document, xhtml namespace is default');
;
$doc = DOM\HTMLDocument::createEmpty();
lookupNamespaceURI($doc, 'xml', null, 'Document without documentElement has no namespace URI matching "xml"');
lookupNamespaceURI($doc, 'xmlns', null, 'Document without documentElement has no namespace URI matching "xmlns"');
;
$attr = $document->createAttribute('foo');
lookupNamespaceURI($attr, 'xml', null, 'Disconnected Attr has no namespace URI matching "xml"');
lookupNamespaceURI($attr, 'xmlns', null, 'Disconnected Attr has no namespace URI matching "xmlns"');
$document->getElementsByTagName("body")[0]->setAttributeNode($attr);
lookupNamespaceURI($attr, 'xml', $XML_NS, 'Connected Attr has namespace URI matching "xml"');
lookupNamespaceURI($attr, 'xmlns', $XMLNS_NS, 'Connected Attr no namespace URI matching "xmlns"');
;
$comment = $document->createComment('comment');
$document->appendChild($comment);
lookupNamespaceURI($comment, 'bar', null, 'Comment does not have bar namespace');
;
;