var conf = require('./move.config.js').setting;
var fs = require('fs');
var path = require('path');
var async = require("async");
var err_file = [],
    success_file = [];

function mkdir_old(conf, callback) {
    //创建old资料夹
    fs.exists(conf.move_path + 'old', function (exists) {
        if (exists) {
            console.log(conf.move_path + 'old' + '目录已存在')
        } else {
            fs.mkdir(conf.move_path + 'old', function (err) {
                if (err)
                    throw err
                console.log(conf.move_path + 'old' + '创建目录成功')
            })
        }
        callback(true)
    });
}

function move(conf, callback) {
    console.log('开始搬移档案')
    console.log('原档案位置：' + conf.file_path)
    console.log('后档案位置：' + conf.move_path)
    async.forEachOf(conf.file_name, function (file, index, callback) {
        //搬移新系统档案
        fs.rename(conf.file_path + file, conf.move_path + file, function (err) {
            if (err) {
                err_file.push(file)
                return
            }
            success_file.push(file)
        });

        //搬移旧系统档案
        fs.rename(conf.file_path + conf.old_file_name[index], conf.move_path + 'old/' + file, function (err) {
            if (err) {
                err_file.push(conf.old_file_name[index])
                return
            }
            success_file.push(conf.old_file_name[index])
        });
        callback();
    }, function (err) {
        if (err) console.log(err);

        callback(true)

        // configs is now a map of JSON data

    })


}

//执行程式
mkdir_old(conf, function (done) {
    if (done) {
        move(conf, function (e) {
            if (e) {
                console.log(conf.message.err)
                console.log(JSON.stringify(err_file))
                console.log(conf.message.success)
                console.log(JSON.stringify(success_file))
            }
        });
    }
})