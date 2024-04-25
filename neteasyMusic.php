<?php
if(!isset($_GET['word'])){
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>网易云音乐搜索</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
</head>
<body>
    <p>
        <form method="get">
            <label>搜索关键词</label>
            <input name="word" type="text" class="form-control">
            <button class="btn btn-primary">提交</button>
        </form>
    </p>
    <p>API来自 https://api.xingzhige.com/</p>
    <script src="jquery.3.5.1.min.js"></script>
    <script src="bootstrap.min.js"></script>
</body>
</html>
    
<?php
}else if(!isset($_GET['n'])){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.xingzhige.com/API/NetEase_CloudMusic_new/?name=".urlencode($_GET['word']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    $response = curl_exec($ch);
    if(curl_errno($ch)) {
        die("<script>alert('network error');history.go(-1);</script>");
    }
    curl_close($ch);
    $data = json_decode($response,true);
    ?>
    <html lang="zh">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>网易云音乐搜索</title>
        <link rel="stylesheet" href="./bootstrap.min.css">
    </head>
    <body>

    <?php
    foreach ($data['data'] as $index=>$row){
        ?>
        <div>
            <?=$row['songname']?> - <?=$row['name']?> - <?=$row['album']?> (<?=$row['pay']?>)
            <form>
                <input type="hidden" name="n" value="<?=($index+1)?>">
                <input type="hidden" name="word" value="<?=$_GET['word']?>">
                <button class="btn btn-primary">选择</button>
            </form>
        </div>
        <?php
    }
    ?>
        <script src="jquery.3.5.1.min.js"></script>
        <script src="bootstrap.min.js"></script>
    </body>
</html>
    <?php
}else{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.xingzhige.com/API/NetEase_CloudMusic_new/?name=".urlencode($_GET['word'])."&n=".$_GET['n']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    $response = curl_exec($ch);
    if(curl_errno($ch)) {
        die("<script>alert('network error');history.go(-1);</script>");
    }
    curl_close($ch);
    $data = json_decode($response,true);
    if($data['code']!=0){
        die("<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" /><script>alert('".$data['msg']."');history.go(-1);</script>");
    }
    ?>
    <script>
        window.opener.postMessage({
            type:"setForm",
            name:"<?=addslashes($data['data']['songname'])?>-<?=addslashes($data['data']['name'])?>",
            url:"<?=$data['data']['src']?>",
            mediaType:'audio'
        },"*");
        window.close();
    </script>
    <?php
}
?>


