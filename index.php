<?php
session_start() ;
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
include "vendor/autoload.php" ;
include __DIR__."/App/Routes/web.php" ;
