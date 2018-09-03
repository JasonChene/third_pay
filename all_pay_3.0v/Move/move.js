var conf = require('./move.config.js').setting;
var fs = require('fs');
var path = require('path');

console.log(conf.file_path);
console.log(conf.move_path);


function move(conf) {
    conf.file_name.forEach(function (e) {

    });
    fs.rename('./bbb/qqpost.php', './aaa/wxpost.php', function (err) {
        if (err) {
            console.error(err);
            return;
        }
        console.log('重命名成功')
    });

}

move(conf);