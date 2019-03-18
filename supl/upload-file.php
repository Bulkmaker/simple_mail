<?php

//use \Gumlet\ImageResize;
//require_once ("upload_path-local.php");
require_once ("upload_paths.php");
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upload']['error']) ||
        is_array($_FILES['upload']['error'])
    ) {
        throw new RuntimeException('Ошибка загрузки файла.');
    }

    // Check $_FILES['upload']['error'] value.
    switch ($_FILES['upload']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('Ошибка загрузки файла.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Превышен максимальный размер файла (5мб).');
        default:
            throw new RuntimeException('Ошибка загрузки файла (неизвестная ошибка).');
    }

    // You should also check filesize here.
    if ($_FILES['upload']['size'] > 5242880) {
        throw new RuntimeException('Превышен максимальный размер файла (5мб).');
    }

    // DO NOT TRUST $_FILES['upload']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
            $finfo->file($_FILES['upload']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                'webp' => 'image/webp',
                //'doc' => 'application/msword',
                //'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ),
            true
        )) {
        throw new RuntimeException('Недопустимый формат файла.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upload']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.

    $fupl = new FilesUploadPath();

    $tmp_name = md5(substr($_FILES['upload']['name'],0,-4))."-".date("dmYHis");

    /*MOVE ORIGINAL VERSION*/
    $fname = sprintf('%s/%s.%s',
        $fupl->image_path,
        $tmp_name,
        $ext
    );
    if (!move_uploaded_file(
        $_FILES['upload']['tmp_name'],
        $fname
    )) {
        throw new RuntimeException('Ошибка сохранения файла.');
    }
    $full_path = $fupl->image_path."/".$tmp_name.".".$ext;

    $return_path = $fupl->image_path_uploaded.$tmp_name.".".$ext;
    echo $return_path;

} catch (RuntimeException $e) {

    echo $e->getMessage();

}

?>