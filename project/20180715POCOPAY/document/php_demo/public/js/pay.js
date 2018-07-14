$(function () {
    $('ul li').click(function () {
        var $this = $(this);
        var href = $this.attr('href');

        location.href = href;
        return false;
    });


    $('.ajax-form-button').click(function (event) {

        event.preventDefault();

        var data = $('.ajax-form').serialize();
        var url  = $('.ajax-form').attr('action');

        $.ajax(
            {
                url     :url,
                type    :'POST',
                dataType:'JSON',
                data:data,
                success:function (data) {
                    var html = '返回结果:'+data.msg+'<br>';
                    for(var i in data.data) {
                        html += '属性为'+i+':'+data.data[i]+'<br>';
                    }

                    $('.result').html(html);
                    return false;
                }
            }
        );
    });
});



