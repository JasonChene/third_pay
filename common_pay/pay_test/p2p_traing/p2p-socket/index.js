var express = require('express')
var app = express()
//socket.io 必须
var http = require('http').Server(app)
var io = require('socket.io')(http)
//session 必须
var session = require('express-session')

app.use(session({
    secret: 'keyboard cat',
    resave: false,
    saveUninitialized: true
}))

//模板引擎
app.set("view engine", "ejs")
//静态服务
app.use(express.static("./public"))
//汇入静态档案simplepeer.min.js
app.get('/simplepeer.min.js', function (req, res) {
    res.sendFile(__dirname + '/node_modules/simple-peer/simplepeer.min.js')
})

var alluser = [];
var countuser = 0;
//路由 显示首页
app.get("/", function (req, res, next) {

    console.log(countuser + "进来了！");
    res.render("index")

})
//验证并跳转 赋予session
// app.get('/check', function (req, res, next) {
//     var name = req.query.name;
//     if (!name) {
//         res.send("请输入昵称");
//         return;
//     }
//     if (alluser.indexOf(name) != -1) {
//         res.send("用户名已存在");
//         return;
//     }
//     alluser.push(name);
//     //赋予session
//     req.session.name = name;
//     res.redirect("/chatroom");
// });

//进入聊天室
// app.get("/chatroom", function (req, res, next) {
//     //没登入转到首页
//     if (!req.session.name) {
//         res.redirect("/");
//         return;
//     }
//     console.log(req.session.name + '进入聊天室！');
//     io.on("connection", function (socket) {
//         io.emit("hihi", {
//             "name": req.session.name,
//             "chat_msg": '进入聊天室！'
//         });
//     });
//     res.render("chatroom", {
//         "name": req.session.name
//     });
// });

//进入画板
// app.get("/drawroom", function (req, res, next) {
//     //没登入转到首页
//     if (!req.session.name) {
//         res.redirect("/");
//         return;
//     }
//     console.log(req.session.name + '进入画板！');
//     res.render("drawroom", {
//         "name": req.session.name
//     });
// });

io.on("connection", function (socket) {
    socket.on("p2p_room", function (msg) {
        io.emit("p2p_room", msg)
    })
})

//监听
http.listen(3000, '192.168.50.106')