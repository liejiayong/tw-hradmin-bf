<?php
//文件礼包码的上传
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');

//上传文件错误判定
if ($_FILES['Filedata']['error'] > 0) {
    $msg = '错误:';
    switch ($_FILES['code_file']['error']) {
        case 1:
            $msg .= '文件尺寸超过允许的最大上传限度!';
            break;
        case 2:
            $msg .= '文件尺寸超过允许的最大上传限度!';
            break;
        case 3:
            $msg .= '只有部分文件被上传!';
            break;
        case 4:
            $msg .= '没有任何文件被上传!';
            break;
    }

    echo json_encode([
        "msg" => $msg
    ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    die();
}

$file_tmp = $_FILES['Filedata']['tmp_name'];
$fileName = $_FILES['Filedata']['name'];
$fileType = strtolower(strrchr($fileName, "."));

//后缀名判断
if ($fileType != '.txt') {
    echo json_encode([
        "msg" => '错误:文件扩展名不对。只允许上传txt的文件格式'
    ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    die();
}

//文件格式判定 mime
if ($_FILES['Filedata']['type'] != 'text/plain') {
    echo json_encode([
        "msg" => '错误:非法文件格式!'
    ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    die();
}

$tmpContent = trim(file_get_contents($file_tmp));
$codeList = explode("\n", $tmpContent);
$count = count($codeList);

if ($count > 50000) {
    echo json_encode([
        "msg" => "错误:每次上传不能大于5W条,当前文件包含{$count}条。"
    ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    die();
}

if (is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
    if (!move_uploaded_file($file_tmp, 'test_xsk.txt')) {
        //存在则移动完在上传
        echo json_encode([
            "msg" => "文件上传失败!"
        ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
        die();

    }else{
        echo json_encode([
            'ret' => 'success',
            "msg" => "txt文件上传成功"
        ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
        die();
    }
} else {
    echo json_encode([
        "msg" => "错误:可能文件上传被攻击!文件名:". $fileName
    ], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
    die();
}