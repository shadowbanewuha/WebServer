<?php

ob_clean();
//必须至于顶部,多服务器端记录验证码信息，便于用户输入后做校验
session_start();

$w = 140;
$h = 64;

//默认返回的是黑色的照片
$image = imagecreatetruecolor($w, $h);
//将背景设置为白色的
$bgcolor = imagecolorallocate($image, 255, 255, 255);
//将白色铺满地图
imagefill($image, 0, 0, $bgcolor);

//空字符串，每循环一次，追加到字符串后面
$captch_code='';


//验证码为随机四个字符，数字和字母
for ($i=0; $i <4 ; $i++) {
    $fontsize=5;
    $fontcolor=imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));
    //子典。因为o和0，l和1冲突，所以我们字典中不包括易混淆的
    $data='ABCDEFGHJKLMNPQRSTUVWXYZ3456789';
    $fontcontent = substr($data,rand(0,strlen($data)-1) ,1);
    $captch_code.= $fontcontent;

    $x=($i*$w/4)+rand(5,10);
    $y=rand(10,20);
    imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);
}

$_SESSION['verifycode'] = $captch_code;
//为验证码增加干扰元素，控制好颜色，
//点
for ($i=0; $i < 200; $i++) {
    $pointcolor = imagecolorallocate($image,rand(50,180),rand(20,200),rand(0,140));
    imagesetpixel($image, rand(1,$w-1), rand(1,$h-1), $pointcolor);
}

//为验证码增加干扰元素
//线
for ($i=0; $i < 3; $i++) {
    $linecolor = imagecolorallocate($image,rand(80,220),rand(80,220),rand(80,220));
    imageline($image, rand(1,$w-1), rand(1,$h-1),rand(1,$w-1), rand(1,$h-1) ,$linecolor);
}

header('content-type:image/png');
imagepng($image);

//销毁
imagedestroy($image);

echo $captch_code;