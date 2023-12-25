<?php

require '../../dom/driver.inc.php';

error_reporting(E_ALL & ~E_NOTICE);

const XMLNS_URI = 'http://www.w3.org/2000/xmlns/';

function createXmlDoc(){
  $input = '<?xml version="1.0" encoding="UTF-8"?><root><child1>value1</child1></root>';
  return DOM\XMLDocument::createFromString($input);
}

// Returns the root element.
function parse($xmlString) {
  return DOM\XMLDocument::createFromString($xmlString)->documentElement;
}

function xmlSerialize($node) {
  if ($node instanceof DOM\Document) {
    $node = $node->documentElement;
  }
  return $node->ownerDocument->saveXML($node);
}

test(function() {
  $root = createXmlDoc()->documentElement;
  assert_equals(xmlSerialize($root), '<root><child1>value1</child1></root>');
}, 'check XMLSerializer.serializeToString method could parsing xmldoc to string');

test(function() {
  $root = parse('<html><head></head><body><div></div><span></span></body></html>');
  assert_equals(xmlSerialize($root->ownerDocument), '<html><head/><body><div/><span/></body></html>');
}, 'check XMLSerializer.serializeToString method could parsing document to string');

test(function() {
  $root = createXmlDoc()->documentElement;
  $element = $root->ownerDocument->createElementNS('urn:foo', 'another');
  $child1 = $root->firstChild;
  $root->replaceChild($element, $child1);
  $element->appendChild($child1);
  assert_equals(xmlSerialize($root), '<root><another xmlns="urn:foo"><child1 xmlns="">value1</child1></another></root>');
}, 'Check if the default namespace is correctly reset.');

test(function() {
  $root = parse('<root xmlns="urn:bar"><outer xmlns=""><inner>value1</inner></outer></root>');
  assert_equals(xmlSerialize($root), '<root xmlns="urn:bar"><outer xmlns=""><inner>value1</inner></outer></root>');
}, 'Check if there is no redundant empty namespace declaration.');

// https://github.com/w3c/DOM-Parsing/issues/47
test(function() {
  // assert_equals(xmlSerialize(parse('<root><child xmlns=""/></root>')),
                // '<root><child/></root>');
  //assert_equals(xmlSerialize(parse('<root xmlns=""><child xmlns=""/></root>')),
  //              '<root><child/></root>');
  // assert_equals(xmlSerialize(parse('<root xmlns="u1"><child xmlns="u1"/></root>')),
  // '<root xmlns="u1"><child/></root>');
  assert_equals(xmlSerialize(parse('<root><child xmlns=""/></root>')),
                '<root><child xmlns=""/></root>');
  assert_equals(xmlSerialize(parse('<root xmlns=""><child xmlns=""/></root>')),
               '<root xmlns=""><child xmlns=""/></root>');
  assert_equals(xmlSerialize(parse('<root xmlns="u1"><child xmlns="u1"/></root>')),
                '<root xmlns="u1"><child xmlns="u1"/></root>');
}, 'Check if redundant xmlns="..." is dropped.');

test(function() {
  $root = parse('<root xmlns="uri1"/>');
  $child = $root->ownerDocument->createElement('child');
  $child->setAttributeNS(XMLNS_URI, 'xmlns', 'FAIL1');
  $root->appendChild($child);
  $child2 = $root->ownerDocument->createElementNS('uri2', 'child2');
  $child2->setAttributeNS(XMLNS_URI, 'xmlns', 'FAIL2');
  $root->appendChild($child2);
  $child3 = $root->ownerDocument->createElementNS('uri1', 'child3');
  $child3->setAttributeNS(XMLNS_URI, 'xmlns', 'FAIL3');
  $root->appendChild($child3);
  $child4 = $root->ownerDocument->createElementNS('uri4', 'child4');
  $child4->setAttributeNS(XMLNS_URI, 'xmlns', 'uri4');
  $root->appendChild($child4);
  $child5 = $root->ownerDocument->createElement('child5');
  $child5->setAttributeNS(XMLNS_URI, 'xmlns', '');
  $root->appendChild($child5);
  assert_equals(xmlSerialize($root), '<root xmlns="uri1"><child xmlns=""/><child2 xmlns="uri2"/><child3/><child4 xmlns="uri4"/><child5 xmlns=""/></root>');
}, 'Check if inconsistent xmlns="..." is dropped.');

test(function() {
  $root = parse('<r xmlns:xx="uri"></r>');
  $root->setAttributeNS('uri', 'name', 'v');
  assert_equals(xmlSerialize($root), '<r xmlns:xx="uri" xx:name="v"/>');

  $root2 = parse('<r xmlns:xx="uri"><b/></r>');
  $child = $root2->firstChild;
  $child->setAttributeNS('uri', 'name', 'v');
  assert_equals(xmlSerialize($root2), '<r xmlns:xx="uri"><b xx:name="v"/></r>');

  $root3 = parse('<r xmlns:x0="uri" xmlns:x2="uri"><b xmlns:x1="uri"/></r>');
  $child3 = $root3->firstChild;
  $child3->setAttributeNS('uri', 'name', 'v');
  assert_equals(xmlSerialize($root3),
                '<r xmlns:x0="uri" xmlns:x2="uri"><b xmlns:x1="uri" x1:name="v"/></r>',
                'Should choose the nearest prefix');
}, 'Check if an attribute with namespace and no prefix is serialized with the nearest-declared prefix');

// https://github.com/w3c/DOM-Parsing/issues/45
test(function() {
  $root = parse('<el1 xmlns:p="u1" xmlns:q="u1"><el2 xmlns:q="u2"/></el1>');
  $root->firstChild->setAttributeNS('u1', 'name', 'v');
  // assert_equals(xmlSerialize($root),
                // '<el1 xmlns:p="u1" xmlns:q="u1"><el2 xmlns:q="u2" q:name="v"/></el1>');
  assert_equals(xmlSerialize($root),
                '<el1 xmlns:p="u1" xmlns:q="u1"><el2 xmlns:q="u2" p:name="v"/></el1>');
}, 'Check if an attribute with namespace and no prefix is serialized with the nearest-declared prefix even if the prefix is assigned to another namespace.');

test(function() {
  $root = parse('<r xmlns:xx="uri"></r>');
  $root->setAttributeNS('uri', 'p:name', 'v');
  assert_equals(xmlSerialize($root), '<r xmlns:xx="uri" xx:name="v"/>');

  $root2 = parse('<r xmlns:xx="uri"><b/></r>');
  $child = $root2->firstChild;
  $child->setAttributeNS('uri', 'p:name', 'value');
  assert_equals(xmlSerialize($root2),
                '<r xmlns:xx="uri"><b xx:name="value"/></r>');
}, 'Check if the prefix of an attribute is replaced with another existing prefix mapped to the same namespace URI.');

// https://github.com/w3c/DOM-Parsing/issues/29
test(function() {
  $root = parse('<r xmlns:xx="uri"></r>');
  $root->setAttributeNS('uri2', 'p:name', 'value');
  // assert_equals(xmlSerialize($root),
                // '<r xmlns:xx="uri" xmlns:ns1="uri2" ns1:name="value"/>');
  assert_equals(xmlSerialize($root),
                '<r xmlns:xx="uri" xmlns:p="uri2" p:name="value"/>');
}, 'Check if the prefix of an attribute is NOT preserved in a case where neither its prefix nor its namespace URI is not already used.');

test(function() {
  $root = parse('<r xmlns:xx="uri"></r>');
  $root->setAttributeNS('uri2', 'xx:name', 'value');
  assert_equals(xmlSerialize($root),
                '<r xmlns:xx="uri" xmlns:ns1="uri2" ns1:name="value"/>');
}, 'Check if the prefix of an attribute is replaced with a generated one in a case where the prefix is already mapped to a different namespace URI.');

test(function() {
  $root = parse('<root />');
  $root->setAttribute('attr', "\t");
  assert_in_array(xmlSerialize($root), [
    '<root attr="&#9;"/>', '<root attr="&#x9;"/>']);
  $root->setAttribute('attr', "\n");
  assert_in_array(xmlSerialize($root), [
    '<root attr="&#xA;"/>', '<root attr="&#10;"/>']);
  $root->setAttribute('attr', "\r");
  assert_in_array(xmlSerialize($root), [
    '<root attr="&#xD;"/>', '<root attr="&#13;"/>']);
}, 'check XMLSerializer.serializeToString escapes attribute values for roundtripping');

test(function() {
  $root = (DOM\XMLDocument::createEmpty())->createElement('root');
  $root->setAttributeNS('uri1', 'p:foobar', 'value1');
  $root->setAttributeNS(XMLNS_URI, 'xmlns:p', 'uri2');
  assert_equals(xmlSerialize($root), '<root xmlns:ns1="uri1" ns1:foobar="value1" xmlns:p="uri2"/>');
}, 'Check if attribute serialization takes into account of following xmlns:* attributes');

test(function() {
  $root = parse('<root xmlns:p="uri1"><child/></root>');
  $root->firstChild->setAttributeNS('uri2', 'p:foobar', 'v');
  assert_equals(xmlSerialize($root), '<root xmlns:p="uri1"><child xmlns:ns1="uri2" ns1:foobar="v"/></root>');
}, 'Check if attribute serialization takes into account of the same prefix declared in an ancestor element');

test(function() {
  assert_equals(xmlSerialize(parse('<root><child/></root>')), '<root><child/></root>');
  assert_equals(xmlSerialize(parse('<root xmlns="u1"><p:child xmlns:p="u1"/></root>')), '<root xmlns="u1"><child xmlns:p="u1"/></root>');
}, 'Check if start tag serialization drops element prefix if the namespace is same as inherited default namespace.');

test(function() {
  $root = parse('<root xmlns:p1="u1"><child xmlns:p2="u1"/></root>');
  $child2 = $root->ownerDocument->createElementNS('u1', 'child2');
  $root->firstChild->appendChild($child2);
  assert_equals(xmlSerialize($root), '<root xmlns:p1="u1"><child xmlns:p2="u1"><p2:child2/></child></root>');
}, 'Check if start tag serialization finds an appropriate prefix.');

test(function() {
  $root = (DOM\XMLDocument::createEmpty())->createElementNS('uri1', 'p:root');
  $root->setAttributeNS(XMLNS_URI, 'xmlns:p', 'uri2');
  assert_equals(xmlSerialize($root), '<ns1:root xmlns:ns1="uri1" xmlns:p="uri2"/>');
}, 'Check if start tag serialization takes into account of its xmlns:* attributes');

test(function() {
  $root = (DOM\XMLDocument::createEmpty())->createElement('root');
  $root->setAttributeNS(XMLNS_URI, 'xmlns:p', 'uri2');
  $child = $root->ownerDocument->createElementNS('uri1', 'p:child');
  $root->appendChild($child);
  assert_equals(xmlSerialize($root), '<root xmlns:p="uri2"><p:child xmlns:p="uri1"/></root>');
}, 'Check if start tag serialization applied the original prefix even if it is declared in an ancestor element.');

// https://github.com/w3c/DOM-Parsing/issues/52
test(function() {
  // assert_equals(xmlSerialize(parse('<root xmlns:x="uri1"><table xmlns="uri1"></table></root>')),
      // '<root xmlns:x="uri1"><x:table xmlns="uri1"/></root>');
  assert_equals(xmlSerialize(parse('<root xmlns:x="uri1"><table xmlns="uri1"></table></root>')),
      '<root xmlns:x="uri1"><table xmlns="uri1"/></root>');
}, 'Check if start tag serialization does NOT apply the default namespace if its namespace is declared in an ancestor.');

test(function() {
  $root = parse('<root><child1/><child2/></root>');
  $root->firstChild->setAttributeNS('uri1', 'attr1', 'value1');
  $root->firstChild->setAttributeNS('uri2', 'attr2', 'value2');
  $root->lastChild->setAttributeNS('uri3', 'attr3', 'value3');
  assert_equals(xmlSerialize($root), '<root><child1 xmlns:ns1="uri1" ns1:attr1="value1" xmlns:ns2="uri2" ns2:attr2="value2"/><child2 xmlns:ns3="uri3" ns3:attr3="value3"/></root>');
}, 'Check if generated prefixes match to "ns${index}".');

// https://github.com/w3c/DOM-Parsing/issues/44
// According to 'DOM Parsing and Serialization' draft as of 2018-12-11,
// 'generate a prefix' result can conflict with an existing xmlns:ns* declaration.
test(function() {
  $root = parse('<root xmlns:ns2="uri2"><child xmlns:ns1="uri1"/></root>');
  $root->firstChild->setAttributeNS('uri3', 'attr1', 'value1');
  // assert_equals(xmlSerialize($root), '<root xmlns:ns2="uri2"><child xmlns:ns1="uri1" xmlns:ns1="uri3" ns1:attr1="value1"/></root>');
  assert_equals(xmlSerialize($root), '<root xmlns:ns2="uri2"><child xmlns:ns1="uri1" xmlns:ns2="uri3" ns2:attr1="value1"/></root>');
}, 'Check if "ns1" is generated even if the element already has xmlns:ns1.');

test(function() {
  $root = (DOM\XMLDocument::createEmpty())->createElement('root');
  $root->setAttributeNS('http://www.w3.org/1999/xlink', 'href', 'v');
  assert_equals(xmlSerialize($root), '<root xmlns:ns1="http://www.w3.org/1999/xlink" ns1:href="v"/>');

  $root2 = (DOM\XMLDocument::createEmpty())->createElement('root');
  $root2->setAttributeNS('http://www.w3.org/1999/xlink', 'xl:type', 'v');
  assert_equals(xmlSerialize($root2), '<root xmlns:xl="http://www.w3.org/1999/xlink" xl:type="v"/>');
}, 'Check if no special handling for XLink namespace unlike HTML serializer.');

test(function() {
  $document = DOM\HTMLDocument::createEmpty();
  $root = $document->createDocumentFragment();
  $root->append($document->createElement('div'));
  $root->append($document->createElement('span'));
  assert_equals(xmlSerialize($root), '<div xmlns="http://www.w3.org/1999/xhtml"></div><span xmlns="http://www.w3.org/1999/xhtml"></span>');
}, 'Check if document fragment serializes.');
