<?php get_header(['p_title' => $p_title ]) ?>
        <div class="container">
            <div class="card">
                <?php if (isset($user)) : ?>
                    <div class="card_body">
                        <div class="card_text">
                            <?php
                            printf("<h2>%s %s</h2>" , $user['name'] , $user['family'] ) ;
                            //printf('<a href="mailto:%s">%s</a>' , $user['email'] , $user['email']) ;
                            ?>
                        </div>
                        <div class="card_icon">
                            <?php
                                if ($user['picture']) {
                                    printf("<img src='%s' />" , $user['picture'] ) ;
                                }else{
                                    echo '<img src="'.asset("images/email.png").'" />' ;
                                }
                            ?>
                        </div>
                    </div>
                    <ul class="messages"></ul>
                    <form action="<?= route("register") ;?>" id="register" class="register" method="post">
                        <input type="hidden" value="<?= $user['code'] ;?>" name="_code">
                        <div class="form-group">
                            <input autocomplete="off" class="form-control" type="text" name="mobile" placeholder="برای عضویت شماره همراه خود را اینجا وارد نمایید ">
                        </div>
                        <div class="form-group">
                            <input autocomplete="off" class="form-control" type="text" name="captcha" placeholder="کد امنیتی ">
                            <div class="captcha" id="captcha">
                                <div class="refresh" id="refreshCaptcha" onclick="ReCaptcha(this);">&#8635;</div>
                                <img src="<?= route("captchaIndex") ;?>" alt="captcha">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn">انجام شد</button>
                        </div>
                    </form>
                <?php else :?>
                    <div class="card_body">
                        <div class="card_text">
                            <h5 class="text-danger">دعوتنامه یافت نشده است .</h5>
                        </div>
                        <div class="card_icon">
                            <img src="<?= asset("images/email.png"); ?>" />
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
<?php get_footer() ;?>
