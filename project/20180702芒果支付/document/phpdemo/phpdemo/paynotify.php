<?php
file_put_contents('../debug.txt',var_export(['notify'=>$_POST],true));
//exit('fail');
exit('SUCCESS');