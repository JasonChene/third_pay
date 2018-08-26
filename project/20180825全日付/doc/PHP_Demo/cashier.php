<?php
header("Content-type: text/html; charset=utf-8");
$params=$_POST;
$arr = $params;
unset($arr['key']);
ksort($arr);
$buff = "";
foreach ($arr as $x => $x_value){
    if($x_value != '' && !is_array($x_value)){
        $buff .= "{$x}={$x_value}&";
    }
}
$buff.="key={$params['key']}";
unset($params['key']);
$params['sign'] = strtoupper(md5($buff));

?>
<form style='display:none;' name='form1' method='post' action='http://quanrifu.net/cashier'>
         <table>
    <?php foreach ($params as $k=>$v) {?>
        <tr>
            <td><?php echo $k;?>:</td>
            <td><input type="text" name="<?php echo $k;?>" value="<?php echo $v;?>"/></td>
        </tr>
    <?php }?>
        <tr><td></td><td><input type="submit" value="pay"/> </td></tr>
    </table>
</form>
<script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";


