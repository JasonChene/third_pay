var P2P = require('socket.io-p2p');
var io = require('socket.io-client');
var $ = require('jquery');
var socket = io();
var opts = { numClients: 6, peerOpts: { trickle: false }, autoUpgrade: false }
var p2p = new P2P(socket, opts, function () {
    $('#private').attr("disabled", false)
    console.log("p2p连线成功,this is:" + p2p.peerId)
    // p2p.emit('peer-obj', 'Hello there. I am ' + p2p.peerId)
})


//接收socket连线的讯息
p2p.on('peer-msg', function (data) {
    console.log('get data :' + data)
    $('#chatroom').prepend('<li class="list-group-item">' + data.socketID + '：' + data.msg + '</li>')
})

//转成p2p讯息
// p2psocket.on('go-private', function () {
//     p2psocket.upgrade(); // upgrade to peerConnection
//     $
// });

//输入讯息
$('#socketinput').on('keyup', function (e) {
    console.log(e.which);
    if (e.which == 13) {
        msg = $('#socketinput').val()
        p2p.emit('peer-msg', {
            "msg": msg,
            "socketID": p2p.peerId
        })
        $('#chatroom').prepend('<li class="list-group-item">' + p2p.peerId + '：' + msg + '</li>')
        $('#socketinput').val('')
        console.log(p2p);
    }
})

$('#private').on('click', function () {
    $('#private').attr("disabled", true)
    $('#des').text('p2p is locked')
    p2p.useSockets = false
    p2p.emit('go-private', true)
})