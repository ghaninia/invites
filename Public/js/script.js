///////////////////////
//register form invites
//////////////////////
$(document).ready(function () {
    $("form.register").submit(function (e) {
        e.preventDefault() ;
        NProgress.start() ;
        var formParent = $(this).closest(".card") ;
        var form = $(this);
        var inputMobile = $('input[name="mobile"]').val() ;
        var inputCaptcha = $('input[name="captcha"]').val() ;
        var inputBroker = $('input[name="_code"]').val() ;
        var ErrPlace = $(".messages" , formParent ) ;
        var errors = [] ;

        ErrPlace.hide().empty().removeClass("success errors") ;

        if(!checkMobile(inputMobile))
        {
            errors.push("فرمت شماره همراه صحیح نمیباشد .");
        }

        if(!checkNotEmpty(inputCaptcha))
        {
            errors.push("کد امنینی الزامی میباشد.");
        }

        if(errors.length > 0 )
        {
            for(var i = 0 ; i < errors.length ; i++ )
            {
                ErrPlace.append("<li>"+errors[i]+"</li>") ;
            }
            ErrPlace.addClass("errors").fadeIn(300);
        }else{
            ErrPlace.removeClass("errors").fadeOut(300);
            $.ajax({
                url : $(this).attr("action") ,
                data : {
                    mobile : inputMobile ,
                    captcha : inputCaptcha ,
                    _code : inputBroker
                } ,
                dataType : "json" ,
                type : "POST" ,
                success : function (response) {
                    if(response.status == false)
                    {
                        var errors = response.errors ;
                        for(var i = 0 ; i < errors.length ; i++ )
                        {
                            ErrPlace.append("<li>"+errors[i]+"</li>") ;
                        }
                        ErrPlace.addClass("errors").fadeIn(300);
                    }else{
                        var message = response['message'] ;
                        var links   = response['links'] ;
                        var place = $('<div class="downloadButton"></div>');
                        for (var link in links) {
                          var obj = links[link] ;
                          place.append("<a href='"+obj['link']+"'><img src='"+obj['icon']+"'><p>"+obj['text']+"</p></a>")
                        } ;
                        form.remove() ;
                        formParent.append(place) ;
                        ErrPlace.append("<li>"+message+"</li>").addClass("success").fadeIn(300);

                    }
					ReCaptcha("#refreshCaptcha") ;
                }
            }) ;

        }
        NProgress.done() ;
    });
});
//////////////////////
//login form invites
//////////////////////
$(document).ready(function () {
    $("form.login").submit(function (e){
        e.preventDefault() ;
        NProgress.start() ;
        var formParent = $(this).closest(".card") ;
        var form = $(this);
        var inputPassword = $('input[name="password"]').val() ;
        var inputCaptcha = $('input[name="captcha"]').val() ;
        var inputBroker = $('input[name="_code"]').val() ;
        var ErrPlace = $(".messages" , formParent ) ;
        var errors = [] ;
        ErrPlace.hide().empty().removeClass("success errors") ;
        if(!checkNotEmpty(inputPassword))
        {
            errors.push("گذرواژه الزامی میباشد.");
        }
        if(!checkNotEmpty(inputCaptcha))
        {
            errors.push("کد امنینی الزامی میباشد.");
        }
        if(errors.length > 0 )
        {
            for(var i = 0 ; i < errors.length ; i++ )
            {
                ErrPlace.append("<li>"+errors[i]+"</li>") ;
            }
            ErrPlace.addClass("errors").fadeIn(300);
        }else {
            ErrPlace.removeClass("errors").fadeOut(300);
            $.ajax({
                url : $(this).attr("action") ,
                data : {
                    password : inputPassword ,
                    captcha : inputCaptcha ,
                    _code : inputBroker
                } ,
                dataType : "json" ,
                type : "POST" ,
                success : function (response) {
                    if(response.status == false)
                    {
                        var errors = response.errors ;
                        for(var i = 0 ; i < errors.length ; i++ )
                        {
                            ErrPlace.append("<li>"+errors[i]+"</li>") ;
                        }
                        ErrPlace.addClass("errors").fadeIn(300);
                    }else{
                        var message = response['message'] ;
                        ErrPlace.append("<li>"+message+"</li>").addClass("success").fadeIn(300);
                        setTimeout(function () {
                            window.location = response['redirect'];
                        } , 300 );
                    }
                    ReCaptcha("#refreshCaptcha") ;
                }
            }) ;
        }

        NProgress.done() ;
    });
});
//check mobile number
function checkMobile(mobile){
    var regx = /^(((\+|00)98)|0)?9[0123456789]\d{8}$/ ;
    return regx.test(mobile) ;
}
//check not empty
function checkNotEmpty(item) {
    var item = $.trim(item) ;
    if(item.length > 0)
        return true ;
    return false ;
}
//refresh captcha
function ReCaptcha(item) {
    var item = $(item) ;
    var captcha = item.closest("#captcha") ;
    var FormGroup = captcha.closest(".form-group") ;
    var Img = $("img" ,captcha);
    var ImgSrc = Img.attr("src") ;
    Img.attr("src" , ImgSrc) ;
    $("input" , FormGroup).val("") ;
}
//each number counter
$(document).ready(function () {
   $(".widget .widget_count").each(function () {
        var count = $(".count" , this );
        var countText = count.text() ;
        var i = 0 ;
        var interval = setInterval(function () {
            if (countText >= i )
            {
                count.text(i++) ;
            }else{
                clearInterval(interval) ;
            }
        } , 10 );
   });
});
// date form
$(document).ready(function () {
    var to, from;
    to = $(".date-to-caption").persianDatepicker({
        inline: false ,
        altField: '.date-to',
        initialValue: false,
        observer: true,
        altFormat: 'X',
        onSelect: function (unix) {
            to.touched = true;
            if (from && from.options && from.options.maxDate != unix) {
                var cachedValue = from.getState().selected.unixDate;
                from.options = {maxDate: unix};
                if (from.touched) {
                    from.setDate(cachedValue);
                }
            }
        }
    });
    from = $(".date-from-caption").persianDatepicker({
        inline: false ,
        altField: '.date-from',
        initialValue: false,
        observer: true,
        altFormat: 'X',
        onSelect: function (unix) {
            from.touched = true;
            if (to && to.options && to.options.minDate != unix) {
                var cachedValue = to.getState().selected.unixDate;
                to.options = {minDate: unix};
                if (to.touched) {
                    to.setDate(cachedValue);
                }
            }
        }
    });
});
// modal
$(document).ready(function () {
    var d = $('#somedialog');
    $('.open').click(function(e){
        d.removeClass('dialog-close');
        d.addClass('dialog-open');
    });
    $('.close, .dialog-overlay').click(function(e){
        d.removeClass('dialog-open');
        d.addClass('dialog-close');
    });
})
