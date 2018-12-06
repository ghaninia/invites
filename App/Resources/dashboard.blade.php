<?php get_header(['p_title' => $p_title]) ;?>
    <div class="container-dashboard">
        <div class="sidebar sidebar--admin">
            <button class="button button--add open">لینک دعوت نامه</button>
            <nav>
                <ul>
                    <li><a href="<?= route("DashboardInvitesCode") ;?>"><i class="zmdi zmdi-apps"></i>داشبورد</a></li>
                    <li><a href="<?= route("DashboardInvitesCode" , ['index' => 'registration']) ;?>"><i class="zmdi zmdi-account-box-mail zmdi-hc-fw"></i>عضو غیرفعال</a></li>
                    <li><a href="<?= route("DashboardInvitesCode" , ['index' => 'installed']) ;?>"><i class="zmdi zmdi-accounts-list-alt zmdi-hc-fw"></i>عضو فعال</a></li>
                    <li><a href="<?= route("DashboardInvitesCode" , ['index' => 'total']) ;?>"><i class="zmdi zmdi-accounts zmdi-hc-fw"></i>کل اعضا</a></li>
                </ul>
            </nav>
            <ul class="foot">
                <li><a href="<?= route('DashboardLogoutInvitesCode') ;?>"><i class="zmdi zmdi-long-arrow-return zmdi-hc-fw"></i><p>خروج</p></a></li>
            </ul>
        </div>
        <div class="main main--team">

            <!------------------>
            <!------------------>
            <!-- form search --->
            <!------------------>
            <!------------------>
            <?php if ( in_array( $indexRequest , ['registration' , 'installed' ,'total']) ) : ?>
                <small class="main-title">
                    <?php if($indexRequest == 'registration') :?>
                    لیست دعوت شدگانی که تابه حال فقط شماره همراه خود را وارد نموده اند.
                    <?php elseif($indexRequest == 'installed') :?>
                    لیست دعوت شدگانی که پُرسَپ را دانلود کرده و حداقل یکبار وارد برنامه شده اند .
                    <?php endif ;?>
                </small>
                <form action="<?= route("DashboardInvitesCode") ;?>" method="get" class="search">
                    <div class="form-group">
                        <input type="hidden" value="<?= $indexRequest ;?>" name="index" >
                        <input name="q" autocomplete="off" type="text" value="<?= request()->input("q") ;?>" placeholder="دنبال چه کسی میگردید ؟" class="form-control">
                    </div>
                    <!----------------------->
                    <!--date from to date --->
                    <div class="form-group">
                        <div class="date date-from-caption">
                            <input type="hidden" name="date-from" value="<?= request()->input("date-from") ;?>" class="date-from">
                            <i class="zmdi zmdi-time zmdi-hc-fw"></i>
                            <span>شروع از تاریخ</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="date date-to-caption">
                            <input type="hidden" name="date-to" value="<?= request()->input("date-to") ;?>" class="date-to">
                            <i class="zmdi zmdi-time zmdi-hc-fw"></i>
                            <span>تا تاریخ</span>
                        </div>
                    </div>
                    <button class="btn btn-danger btn-xs"><i class="zmdi zmdi-search zmdi-hc-fw"></i></button>
                </form>
                <?php if (isset($members) && is_array($members) ) : ?>
                    <?php if(count($members) > 0) :?>
                        <table class="dataTable">
                            <thead>
                            <tr>
                                <?php if($indexRequest == 'installed') :?>
                                <th><p>نام کاربری</p><?= OrderByIcon("username" , true ) ;?></th>
                                <?php endif ;?>
                                <th><p>شماره همراه</p></th>
                                <th><p>تاریخ عضویت</p><?= OrderByIcon("create_time" , true ) ;?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($members as $memeber) :?>
                            <tr>
                                <?php if($indexRequest == 'installed') :?>
                                    <td><small><?= $memeber['username'] ;?></small></td>
                                <?php endif ;?>
                                <td dir="ltr"><?= MobileNumberStar($memeber['mobile']) ;?></td>
                                <td><?= JdateTimeFormat($memeber)?></td>
                            </tr>
                            <?php endforeach ; ?>
                            </tbody>
                        </table>
                    <?php else :?>
                        <div class="notfound">هیچ عضوی ثبت نشده است .</div>
                    <?php endif ;?>
                    <?php if(isset($member_paginate)) echo $member_paginate ;?>
                <?php endif ;?>
            <?php else :?>
                <div class="widget">
                    <div class="widget_count">
                        <span class="count"><?= count($registration) ;?></span>
                        <span class="title">عضو غیرفعال</span>
                    </div>
                    <div class="widget_count">
                        <span class="count"><?= count($installed) ;?></span>
                        <span class="title">عضو فعال</span>
                    </div>
                    <div class="widget_count">
                        <span class="count"><?= $total ;?></span>
                        <span class="title">تعداد اعضا</span>
                    </div>
                </div>
            <?php endif ;?>
        </div>
    </div>
    <div id="somedialog" class="dialog">
        <div class="dialog-overlay"></div>
        <div class="dialog-content">
            <h4 class="title">لینک دعوت نامه</h4>
            <div class="form-group">
                <input type="text" class="form-control" value="<?= route('invitesCode' , [auth()->user()['code']]) ;?>" disabled>
            </div>
        </div>
    </div>
<?php get_footer() ;?>