var express = require('express');
var app = express();
//socket.io-p2p 必须
var server = require('http').Server(app);
var io = require('socket.io')(server);
var p2p = require('socket.io-p2p-server').Server;
io.use(p2p);
//session 必须
var session = require('express-session');
app.use(session({
  secret: 'keyboard cat',
  resave: false,
  saveUninitialized: true
}));

//模板引擎
app.set("view engine", "ejs");
//静态服务
app.use(express.static("./public"));
//汇入静态档案socketiop2p.min.js
// app.get('/socketiop2p.min.js', function (req, res) {
//   res.sendFile(__dirname + '/node_modules/socket.io-p2p/socketiop2p.min.js')
// })

var alluser = [];
var alluser_count = new Object();
//路由 显示首页
app.get("/", function (req, res, next) {
  res.render("index");
});

//验证并跳转 赋予session
app.get('/check', function (req, res, next) {
  var name = req.query.name;
  // if (!name) {
  //   res.send("请输入昵称");
  //   return;
  // }
  // if (alluser.indexOf(name) != -1) {
  //   res.send("用户名已存在");
  //   return;
  // }
  alluser.push(name);
  alluser_count[name] = 0;
  //赋予session
  req.session.name = name;
  res.redirect("/main");
});

//进入聊天室
// app.get("/chatroom", function (req, res, next) {
//   //没登入转到首页
//   if (!req.session.name) {
//     res.redirect("/");
//     return;
//   }
//   console.log(req.session.name + '进入聊天室！');
//   io.on("connection", function (data) {
//     io.emit("hihi", {
//       "name": req.session.name,
//       "chat_msg": '进入聊天室！'
//     });
//   });
//   res.render("chatroom", {
//     "name": req.session.name
//   });
// });

//进入p2p房间
var count = 0;
app.get("/p2proom", function (req, res, next) {

  if (!req.session.name) {
    res.redirect("/");
    return;
  }
  if (alluser_count[req.session.name] == 0) {
    count = count + 1;
    alluser_count[req.session.name] = count;
    console.log(alluser_count);
  }
  console.log(req.session.name + '进入p2p房间！');
  io.emit('peer-msg', {
    "msg": "玩家已加入！",
    "socketID": req.session.name
  })
  res.render("p2proom", {
    "name": req.session.name
  });
});
/** 游戏控制START------------------ */
//进入对战选单
app.get('/main', function (req, res, next) {
  if (!req.session.name) {
    res.redirect("/");
    return;
  }
  res.render("main");
})

//进入四人房
app.get('/fish4', function (req, res, next) {
  if (!req.session.name) {
    res.redirect("/");
    return;
  }
  res.render("fish4");
})

var rooms = [];  //rooms阵列，存储房间号及人数

io.on('connection', function (socket) {
  //分配房间
  var creatroom = true;
  rooms.find(function (room, index, arr) {
    if (room.people.indexOf() == socket.id) {
      return
    }
    if (room.people.length < 3) {
      room.people.push(socket.id)
      socket.join(room.roomID)
      creatroom = false
    } else if (room.people.length == 3) {
      room.people.push(socket.id)
      socket.to(room.roomID).emit('start', 0)
      creatroom = false
    }
  })

  if (creatroom) {
    room = {
      roomID: Math.random().toString().substr(2),
      people: new Array(),
      sign: 'sign001'
    }
    rooms.push(room)
    room.people.push(socket.id)
    socket.join(room.roomID)
    console.log('creating=>' + socket.id + ' at ' + room.roomID);
    socket.to(room.roomID).emit('player1')
  }


  console.log('rooms data:');
  console.log(rooms);
})

/** 游戏控制END------------------ */
io.on('connection', function (socket) {
  socket.on('peer-msg', function (data) {
    console.log('Message from peer: %s', JSON.stringify(data))
    socket.broadcast.emit('peer-msg', {
      "msg": data.msg,
      "socketID": data.socketID
    })
  })

  socket.on('go-private', function (data) {
    socket.broadcast.emit('go-private', data)
  })

  socket.on("hihi", function (msg) {
    io.emit("hihi", msg);
  })
  // socket.on("draw", function (msg) {
  //   io.emit("draw", msg);
  // })
  // socket.on("p2pdata", function (msg) {
  //   io.emit("p2pdata", msg);
  //   console.log(msg.number);
  //   console.log(msg.uid);
  // })
  // socket.on("peer-obj", function (msg) {
  //   console.log(msg);
  // })
})



//监听
server.listen(3000, '192.168.50.106', function () {
  console.log("open at http://192.168.50.106:3000");
});