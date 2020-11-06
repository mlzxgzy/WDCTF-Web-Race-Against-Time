<html>
    <head>
        <meta charset="UTF-8">
    </head>

    <body>
        <form action="index.php?upload" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" />
        <input type="submit" value="upload" />
        </form>
    </body>
</html>

<?php
if (isset($_GET['ls']))
{
    system("ls -al uploads");
}


if (isset($_GET['upload']))
{
    $fileType = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);

    if ($_FILES["file"]["size"] > 500000) {
        echo "Your file is too large.";
    }

    if($fileType != "zip" ) {
        echo "Only ZIP files are allowed.";
    }

    $zip = new ZipArchive();

    $r = $zip->open($_FILES['file']['tmp_name']);
    if($r!==true){
        echo "open error code: {$r}\n";
        exit();
    }

    $zip->extractTo('uploads/');
    $r = $zip->close();
    echo $r?'success':'fail';

    $di = new RecursiveDirectoryIterator("uploads/", FilesystemIterator::SKIP_DOTS);
    $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

    foreach ( $ri as $img ) {
        if ($img->isDir()) {
            rmdir($img);
        } else {
            $imgType = pathinfo($img,PATHINFO_EXTENSION);
            if ($imgType !== "gif" && $imgType !== "jpeg" && $imgType !== "jpg" && $imgType !== "png") {
                unlink($img);
            }
        }
    }
}else{
    show_source(__FILE__);
}

?>
