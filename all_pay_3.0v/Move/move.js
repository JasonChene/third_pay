var conf = require('./move.config.js').setting;
var fs = require('fs');
var err_file = [],
    success_file = [];

async function asyncForEach(array, callback) {
    for (let index = 0; index < array.length; index++) {
        await callback(array[index], index, array)
    }
}

function existsAsync(path) {
    return new Promise(function (resolve) {
        fs.exists(path, resolve)
    });
}

function renameAsync(path, oldpath, file) {
    return new Promise(function (res, rej) {
        fs.rename(path, oldpath, function (err) {
            if (err) {
                err_file.push(file)
                res(true)
            } else {
                success_file.push(file)
                res(true)
            }
        });
    });
}

async function move(conf) {
    console.log('开始搬移档案')
    console.log('原档案位置：' + conf.file_path)
    console.log('后档案位置：' + conf.move_path)
    exists = await existsAsync(conf.move_path + 'old')
    if (!exists) fs.mkdir(conf.move_path + 'old')
    console.log('old目录已存在')
    await asyncForEach(conf.file_name, async function (file, index) {
        await renameAsync(conf.file_path + file, conf.move_path + file, file);
        await renameAsync(conf.file_path + conf.old_file_name[index], conf.move_path + 'old/' + file, conf.old_file_name[index]);
    })
    console.log('搬移成功');
    console.log(conf.message.err)
    console.log(JSON.stringify(err_file))
    console.log(conf.message.success)
    console.log(JSON.stringify(success_file))
}

move(conf);