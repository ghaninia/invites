<!Doctype html>
<html lang="fa">
    <head>
        <meta charset="utf-8">
        <title>خطای 404</title>
        <link rel="stylesheet" href="<?= asset("css/error_page.min.css") ;?>">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    </head>
    <body>
        <div id='oopss'>
            <div id='error-text'>
                <span>404</span>
                <p>
                    <?php
                    if (isset($message)) {
                        echo $message ;
                    }else{
                        echo "صفحه مورد نظر یافت نشد ." ;
                    }
                    ?>
                </p>
                <p class='hmpg'><a href='#' class="back">Back To Homepage</a></p>
            </div>
        </div>
    </body>
</html>
