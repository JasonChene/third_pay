<?php
include_once('./mysql.config.php');
header("Content-type:text/html; charset=utf-8");
$params = array(':is' => 1);
$sql = "select * from pay_set where is_wy=:is OR is_wx=:is OR is_zfb=:is OR is_qq=:is";
$stmt = $mydata1_db->prepare($sql);
$stmt->execute($params);

?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
  crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
  crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
  crossorigin="anonymous"></script>
<link rel="stylesheet" href="chosen.css">
<script src="chosen.jquery.js"></script>

<head>
  <meta charset="utf-8">
  <title>新系统付款測試</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style media="screen">
    label {
      font-size: 1.2rem;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
    }

    .col-xl-6 {
      width: 800px;
      margin: 100px auto;
      border: 1px solid #b3a9a9;
    }

    div {
      width: 600px;
      margin: 10px auto;
      border: 5px;
    }

    h1 {
      margin-bottom: 30px;
      background-color: #90de79;
      border-bottom: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    button {
      margin: 2px auto;
    }

    .form-group {
      margin-bottom: 40px;
    }

    .box {
      display: flex;
      justify-content: space-around;
    }

    .div-nowrap {
      width: 190px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }
  </style>
</head>

<body>
  <div class="col-xl-6">
    <div>
      <h1>新系统付款測試</h1>
      <div class="box">
        <div class="form-group div-nowrap">
          <label>請填网域位置</label>
          <br>
          <input id="domain" type="text" class="form-control form-controlCum" name="domain" placeholder="domain" value="pay7.5566205.com" disabled>
        </div>
        <div class="form-group div-nowrap" id="select_div">
          <label>第三方名称</label>
          <br>
          <select style="width:96%;height:35px;margin-left:2%;" name="pay_name" id="pay_name_select">
            <?php
            while ($row = $stmt->fetch()) {
              ?>
              <option id="pay_name" type="text" class="form-control form-controlCum" name="pay_name" placeholder="第三方名称" value=<?php echo $row['pay_name'] ?>><?php echo $row['pay_name'] ?></option>
              <?
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>订单金额(單位元)</label>
        <input id="MOAmount" type="text" class="form-control" name="MOAmount" placeholder="MOAmount" value="100">
      </div>
      <div class="form-group">
        <label>bank_code</label>
        <select style="width:96%;height:35px;margin-left:2%;" name="pay_name" id="bank_code">
            <option>请选择第三方</option>
        </select>
      </div>
      <label>post</label>
      <button id="post_wy" type="submit" class="btn btn-outline-primary">网银</button>
      <button id="post_yl" type="submit" class="btn btn-outline-primary">银联钱包</button>
      <button id="post_ylfs" type="submit" class="btn btn-outline-primary">银联钱包反扫</button>
      <button id="post_ylkj" type="submit" class="btn btn-outline-primary">银联快捷</button>
      <br>
      <label>wxpost</label>
      <button id="wxpost_wx" type="submit" class="btn btn-outline-secondary">微信</button>
      <button id="wxpost_wxfs" type="submit" class="btn btn-outline-secondary">微信反扫</button>
      <button id="wxpost_jd" type="submit" class="btn btn-outline-secondary">京东</button>
      <button id="wxpost_bd" type="submit" class="btn btn-outline-secondary">百度</button>
      <br>
      <label>qqpost</label>
      <button id="qqpost_qq" type="submit" class="btn btn-outline-success">QQ</button>
      <button id="qqpost_qqfs" type="submit" class="btn btn-outline-success">QQ反扫</button>
      <br>
      <label>zfbpost.php</label>
      <button id="zfbpost_zfb" type="submit" class="btn btn-outline-success">支付宝</button>
      <button id="zfbpost_zfbfs" type="submit" class="btn btn-outline-success">支付宝反扫</button>
      <br>
      <label>wxqqpost</label>
      <button id="wxqqpost_wx" type="submit" class="btn btn-outline-danger">微信</button>
      <button id="wxqqpost_wxfs" type="submit" class="btn btn-outline-danger">微信反扫</button>
      <button id="wxqqpost_qq" type="submit" class="btn btn-outline-danger">QQ</button>
      <button id="wxqqpost_qqfs" type="submit" class="btn btn-outline-danger">QQ反扫</button>
      <br>
      <label>wxqqjdpost</label>
      <button id="wxqqjdpost_wx" type="submit" class="btn btn-outline-warning">微信</button>
      <button id="wxqqjdpost_wxfs" type="submit" class="btn btn-outline-warning">微信反扫</button>
      <button id="wxqqjdpost_qq" type="submit" class="btn btn-outline-warning">QQ</button>
      <button id="wxqqjdpost_qqfs" type="submit" class="btn btn-outline-warning">QQ反扫</button>
      <button id="wxqqjdpost_jd" type="submit" class="btn btn-outline-warning">京东</button>
      <br>
      <label>wxqqjdbdpost</label>
      <button id="wxqqjdbdpost_wx" type="submit" class="btn btn-outline-info">微信</button>
      <button id="wxqqjdbdpost_wxfs" type="submit" class="btn btn-outline-info">微信反扫</button>
      <button id="wxqqjdbdpost_qq" type="submit" class="btn btn-outline-info">QQ</button>
      <button id="wxqqjdbdpost_qqfs" type="submit" class="btn btn-outline-info">QQ反扫</button>
      <button id="wxqqjdbdpost_jd" type="submit" class="btn btn-outline-info">京东</button>
      <button id="wxqqjdbdpost_bd" type="submit" class="btn btn-outline-info">百度</button>
    </div>
    <div>
    <h1>开始查询订单</h1>
    <form  class="form-group div-nowrap" action="index.php" method="get">
        <select class="form-control" style="width:96%;height:35px;margin-left:2%;" name="pay_name">
            <?php
            $stmt = $mydata1_db->prepare($sql);
            $stmt->execute($params);
            while ($row = $stmt->fetch()) {
                ?>
              <option id="pay_name" type="text" class="form-control form-controlCum" name="pay_name" placeholder="第三方名称" value=<?php echo $row['pay_name'] ?>><?php echo $row['pay_name'] ?></option>
              <?
            }
            ?>
        </select>
        <input class="form-control" name="first_time" type="date" id="first_time">
        <input class="form-control" type="submit" id="find" value="查找">
    </form>
    <?php 
        echo ($_GET["first_time"]);
        echo ($_GET["pay_name"]);
        if (isset($_GET["first_time"])) {
            $nowtime = date("Y-m-d H:i:s");
            $params2 = array(':first_time' => $_GET["first_time"].' '.date("H:i:s"),':second_time' => $nowtime,':pay_name' => '%'.$_GET["pay_name"].'%');
            $sql2 = "select m_order,status,about,m_make_time,update_time,operator,top_uid,m_value from k_money where m_make_time >= :first_time and m_make_time <= :second_time and operator Like :pay_name";
            $stmt2 = $mydata1_db->prepare($sql2);
            $stmt2->execute($params2);
                while ($row2 = $stmt2->fetch()) {
                    ?>
                        <p>
                        <a class="btn btn-primary" data-toggle="collapse" href="#<?php echo '订单号：'.$row2['m_order']?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <?php echo '订单号：'.$row2['m_order']?>
                        </a>
                        </p>
                        <div class="collapse" id="<?php echo '订单号：'.$row2['m_order']?>">
                            <div class="card card-body">
                                <ul>
                                    <li><?php echo 'status:'.$row2['status']?></li>
                                    <li><?php echo 'about:'.$row2['about']?></li>
                                    <li><?php echo 'm_make_time:'.$row2['m_make_time']?></li>
                                    <li><?php echo 'update_time:'.$row2['update_time']?></li>
                                    <li><?php echo 'operator:'.$row2['operator']?></li>
                                    <li><?php echo 'top_uid:'.$row2['top_uid']?></li>
                                    <li><?php echo 'm_value:'.$row2['m_value']?></li>
                                </ul>
                            </div>
                        </div>
                    <?
                }
        }
            ?>

    </div>
  </div>
</body>

</html>
<script type="text/javascript">
$(document).ready(function() {
    $(function(){
        $('#pay_name_select').chosen();
    });
  //post
  $('#post_wy').on('click',function(){
      str = 'file_name=post.php&';
      str = str + 'pay_type=&';
      console.log(str);
      jump_url(str);
  });
  $('#post_yl').on('click',function(){
      str = 'file_name=post.php&';
      str = str + 'pay_type=银联钱包&';
      console.log(str);
      jump_url(str);
  });
  $('#post_ylfs').on('click',function(){
      str = 'file_name=post.php&';
      str = str + 'pay_type=银联钱包反扫&';
      console.log(str);
      jump_url(str);
  });
  $('#post_ylkj').on('click',function(){
      str = 'file_name=post.php&';
      str = str + 'pay_type=银联快捷&';
      console.log(str);
      jump_url(str);
  });
  //wxpost
  $('#wxpost_wx').on('click',function(){
      str = 'file_name=wxpost.php&';
      str = str + 'pay_type=&';
      console.log(str);
      jump_url(str);
  });
  $('#wxpost_wxfs').on('click',function(){
      str = 'file_name=wxpost.php&';
      str = str + 'pay_type=微信反扫&';
      console.log(str);
      jump_url(str);
  });
  $('#wxpost_jd').on('click',function(){
      str = 'file_name=wxpost.php&';
      str = str + 'pay_type=京东钱包&';
      console.log(str);
      jump_url(str);
  });
  $('#wxpost_bd').on('click',function(){
      str = 'file_name=wxpost.php&';
      str = str + 'pay_type=百度钱包&';
      console.log(str);
      jump_url(str);
  });
  //qqpost
  $('#qqpost_qq').on('click',function(){
      str = 'file_name=qqpost.php&';
      str = str + 'pay_type=&';
      console.log(str);
      jump_url(str);
  });
  $('#qqpost_qqfs').on('click',function(){
      str = 'file_name=qqpost.php&';
      str = str + 'pay_type=QQ反扫&';
      console.log(str);
      jump_url(str);
  });
  //zfbpost
  $('#zfbpost_zfb').on('click',function(){
      str = 'file_name=zfbpost.php&';
      str = str + 'pay_type=&';
      console.log(str);
      jump_url(str);
  });
  $('#zfbpost_zfbfs').on('click',function(){
      str = 'file_name=zfbpost.php&';
      str = str + 'pay_type=支付宝反扫&';
      console.log(str);
      jump_url(str);
  });
  //wxqqpost
  $('#wxqqpost_wx').on('click',function(){
      str = 'file_name=wxqqpost.php&';
      str = str + 'pay_type=&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqpost_wxfs').on('click',function(){
      str = 'file_name=wxqqpost.php&';
      str = str + 'pay_type=微信反扫&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqpost_qq').on('click',function(){
      str = 'file_name=wxqqpost.php&';
      str = str + 'pay_type=QQ钱包&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqpost_qqfs').on('click',function(){
      str = 'file_name=wxqqpost.php&';
      str = str + 'pay_type=QQ反扫&';
      console.log(str);
      jump_url(str);
  });
  //wxqqjdpost
  $('#wxqqjdpost_wx').on('click',function(){
      str = 'file_name=wxqqjdpost.php&';
      str = str + 'pay_type=&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdpost_wxfs').on('click',function(){
      str = 'file_name=wxqqjdpost.php&';
      str = str + 'pay_type=微信反扫&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdpost_qq').on('click',function(){
      str = 'file_name=wxqqjdpost.php&';
      str = str + 'pay_type=QQ钱包&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdpost_qqfs').on('click',function(){
      str = 'file_name=wxqqjdpost.php&';
      str = str + 'pay_type=QQ反扫&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdpost_jd').on('click',function(){
      str = 'file_name=wxqqjdpost.php&';
      str = str + 'pay_type=京东钱包&';
      console.log(str);
      jump_url(str);
  });
  //wxqqjdbdpost
  $('#wxqqjdbdpost_wx').on('click',function(){
      str = 'file_name=wxqqjdbdpost.php&';
      str = str + 'pay_type=&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdbdpost_wxfs').on('click',function(){
      str = 'file_name=wxqqjdbdpost.php&';
      str = str + 'pay_type=微信反扫&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdbdpost_qq').on('click',function(){
      str = 'file_name=wxqqjdbdpost.php&';
      str = str + 'pay_type=QQ钱包&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdbdpost_qqfs').on('click',function(){
      str = 'file_name=wxqqjdbdpost.php&';
      str = str + 'pay_type=QQ反扫&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdbdpost_jd').on('click',function(){
      str = 'file_name=wxqqjdbdpost.php&';
      str = str + 'pay_type=京东钱包&';
      console.log(str);
      jump_url(str);
  });
  $('#wxqqjdbdpost_bd').on('click',function(){
      str = 'file_name=wxqqjdbdpost.php&';
      str = str + 'pay_type=百度钱包&';
      console.log(str);
      jump_url(str);
  });

  $('#select_div').on('change','#pay_name_select', function(){
       var pj = $(this).val();
       $.ajax({
          url:"./mysql.func.php?pay_name="+pj,
          method:"GET",
          success:function(res){
            $('#bank_code').html(res);
          },
          error:function() {
            alert("SQL error");
          }
       })
     });

});

function jump_url(str){
  let common_str = str + 'domain=' + $('#domain').val() + '&';
  common_str = common_str + 'thrid_name=' + $('#thrid_name').val() + '&';
  common_str = common_str + 'MOAmount=' + $('#MOAmount').val() + '&';
  common_str = common_str + 'pay_name=' + $("select[name='pay_name']").val()  + '&';
  common_str = common_str + 'bank_code=' + $('#bank_code').val()  + '&';
  console.log(common_str);
  window.open('./refresh2.php?'+common_str);
}
</script>
