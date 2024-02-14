// META: title=test WebNN API layerNormalization operation
// META: global=window,dedicatedworker
// META: script=../resources/utils.js
// META: timeout=long

'use strict';

// https://webmachinelearning.github.io/webnn/#api-mlgraphbuilder-layernorm

testWebNNOperation('layerNormalization', buildLayerNorm, 'gpu');