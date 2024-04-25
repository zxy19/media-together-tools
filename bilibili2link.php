<?php
if(!isset($_GET['url'])){
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>哔哩哔哩链接转换工具</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
</head>
<body>
    <p>
        <form method="get">
            <label>请填写URL</label>
            <input name="url" type="url" class="form-control">
            <button class="btn btn-primary">提交</button>
        </form>
    </p>
    <script src="jquery.3.5.1.min.js"></script>
    <script src="bootstrap.min.js"></script>
</body>
</html>
    
<?php
}else{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.xingzhige.com/API/b_parse/?url=".urlencode($_GET['url']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    $response = curl_exec($ch);
    echo($response);
    if(curl_errno($ch)) {
        die("<script>alert('network error');history.go(-1);</script>");
    }
    curl_close($ch);
    $data = json_decode($response,true);
    if($data['code']==0){
        ?>
        <script>
            window.opener.postMessage({
                type:"setForm",
                name:"<?=$data['data']['video']['title']?>",
                url:"<?=$data['data']['video']['url']?>",
                mediaType:'video'
            },"*");
            window.close();
        </script>
        <?php
    }else{
        die("<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" /><script>alert('".$data['msg']."');history.go(-1);</script>");
    }
}
?>