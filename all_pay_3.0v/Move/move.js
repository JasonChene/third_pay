var conf = require('./move.config.js').setting;
var fs = require('fs');
var path = require('path');
var async = require("async");
var err_file = [],
    success_file = [];

async function asyncForEach(array, callback) {
    for (let index = 0; index < array.length; index++) {
        await callback(array[index], index, array)
    }
}


async function mkdir_old(conf) {
    //创建old资料夹
    let exists = await fs.exists(conf.move_path + 'old')

    if (await !exists) fs.mkdir(conf.move_path + 'old')
    console.log(exists);
    return ('old目录已存在')

    //     if (!exists) fs.mkdir(conf.move_path + 'old')
    //     return ('old建立成功')
    //     // if (!exists) {
    //     //     fs.mkdir(conf.move_path + 'old', function (err) {
    //     //         callback(conf.move_path + 'old' + '创建目录成功');
    //     //     })
    //     // } else {
    //     //     callback(conf.move_path + 'old' + '目录已存在');
    //     // }
    // });
}

async function move(conf) {
    console.log('开始搬移档案')
    console.log('原档案位置：' + conf.file_path)
    console.log('后档案位置：' + conf.move_path)
    // let mkdir = await mkdir_old(conf);
    // console.log(mkdir);
    await asyncForEach(conf.file_name, function (file, index) {
        //搬移新系统档案
        fs.rename(conf.file_path + file, conf.move_path + file, function (err) {
            (err) ? err_file.push(file) : success_file.push(file);
        });
        //搬移旧系统档案
        fs.rename(conf.file_path + conf.old_file_name[index], conf.move_path + 'old/' + file, function (err) {
            (err) ? err_file.push(conf.old_file_name[index]) : success_file.push(conf.old_file_name[index]);
        });
    })
    console.log('done');
    console.log(conf.message.err)
    console.log(JSON.stringify(err_file))
    console.log(conf.message.success)
    console.log(JSON.stringify(success_file))


    // conf.file_name.forEach(function (file, index) {
    //     //搬移新系统档案
    //     fs.rename(conf.file_path + file, conf.move_path + file, function (err) {
    //         (err) ? err_file.push(file) : success_file.push(file);
    //     });
    //     //搬移旧系统档案
    //     fs.rename(conf.file_path + conf.old_file_name[index], conf.move_path + 'old/' + file, function (err) {
    //         (err) ? err_file.push(conf.old_file_name[index]) : success_file.push(conf.old_file_name[index]);
    //     });
    // })
}

function log(conf) {
    console.log(conf.message.err)
    console.log(JSON.stringify(err_file))
    console.log(conf.message.success)
    console.log(JSON.stringify(success_file))
}

// async function moveall() {
//     await mkdir_old(conf);
//     await move(conf);
//     await log(conf);
// }

move(conf);