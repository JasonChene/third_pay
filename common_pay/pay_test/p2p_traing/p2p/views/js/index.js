var Peer = require('simple-peer')
var peer1 = new Peer({
  initiator: location.hash === '#init',
  trickle: false,
  // stream: stream
})
var peer2 = new Peer({
  initiator: location.hash === '#init',
  trickle: false,
  // stream: stream
})
var peer3 = new Peer({
  initiator: location.hash === '#init',
  trickle: false,
  // stream: stream
})
peer1.on('signal', function (data) {
  document.getElementById('yourId-to-2').value = JSON.stringify(data)
})
peer2.on('signal', function (data) {
  document.getElementById('yourId-to-2').value = JSON.stringify(data)
})
peer3.on('signal', function (data) {
  document.getElementById('yourId-to-3').value = JSON.stringify(data)
})
peer2.on('connect', function () {
  peer2.send('hi peer2, this is peer1')
})
peer3.on('connect', function () {
  peer3.send('hi peer3, this is peer1')
})

document.getElementById('connect').addEventListener('click', function () {
  var otherId = JSON.parse(document.getElementById('otherId').value)
  peer.signal(otherId)
})

document.getElementById('send').addEventListener('click', function () {
  var yourMessage = document.getElementById('yourMessage').value
  peer2.send(yourMessage)
  peer3.send(yourMessage)
})

peer2.on('data', function (data) {
  document.getElementById('messages').textContent += data + '\n'
})
peer3.on('data', function (data) {
  document.getElementById('messages').textContent += data + '\n'
})
