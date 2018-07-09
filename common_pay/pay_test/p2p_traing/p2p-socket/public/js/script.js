// var Peer = require('simple-peer')
// var $ = jQuery = require('jquery')
// var _ = require('lodash')
// require('bootstrap');
// require('bootstrap/dist/css/bootstrap.css');
var socket = io();

socket.on("p2p_room", function (msg) {
    $("#p2p_room").append('<li>' + msg.msg + '</li>');
});




const peer1 = new SimplePeer({
    initiator: location.hash === '#1',
    trickle: false
});
const peer2 = new SimplePeer({
    initiator: location.hash === '#1',
    trickle: false
});
const peer3 = new SimplePeer({
    initiator: location.hash === '#1',
    trickle: false
});
const peer4 = new SimplePeer({
    initiator: location.hash === '#1',
    trickle: false
});
const peer5 = new SimplePeer({
    initiator: location.hash === '#1',
    trickle: false
});
const peer6 = new SimplePeer({
    initiator: location.hash === '#1',
    trickle: false
});
