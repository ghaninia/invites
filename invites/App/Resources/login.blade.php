<?php get_header(['p_title' => $p_title ]) ;?>

<div class="container">
    <div class="card">
        <h4 class="head"><?= sprintf("ورود به حساب کاربری <small class='text-success'>%s %s</small>" , $user['name'],$user['family']) ;?></h4>
        <div class="messages"></div>
        <form action="<?= route("LoginRequestInvitesCode") ;?>"  class="login" id="login" method="post">
            <input type="hidden" value="<?= $user['code'] ;?>" name="_code">
            <div class="form-group">
                <input autocomplete="off" class="form-control" type="password" name="password" placeholder="گذرواژه">
            </div>
            <div class="form-group">
                <input type="hidden" value="<?= $user['code'] ;?>" name="_code">
                <input autocomplete="off" class="form-control" type="text" name="captcha" placeholder="کد امنیتی ">
                <div class="captcha" id="captcha">
                    <div class="refresh" id="refreshCaptcha" onclick="ReCaptcha(this);">&#8635;</div>
                    <img src="<?= route("captchaIndex") ;?>" alt="captcha">
                </div>
            </div>
            <div class="form-group">
                <button class="btn">ورود</button>
            </div>
        </form>
    </div>
</div>

<?php get_footer() ;?>
