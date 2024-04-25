<?php
if(!isset($_GET['word'])){
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>哔哩哔哩搜索</title>
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
    <script src="jquery.3.5.1.min.js"></script>
    <script src="bootstrap.min.js"></script>
</body>
</html>
    
<?php
}else{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.bilibili.com");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    preg_match_all('/^[Ss]et-Cookie:\s*(.*?);/mi', $response, $matches);
    $cookies = [];
    foreach($matches[1] as $match) {
        parse_str($match, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.bilibili.com/x/web-interface/search/type?keyword=".urlencode($_GET['word'])."&search_type=video");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
    curl_setopt($ch, CURLOPT_REFERER, 'https://search.bilibili.com');
    curl_setopt($ch, CURLOPT_COOKIE, http_build_query($cookies, '', '; '));
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
        <title>哔哩哔哩搜索</title>
        <link rel="stylesheet" href="./bootstrap.min.css">
    </head>
    <body>

    <?php
    foreach ($data['data']['result'] as $index=>$row){
        ?>
        <div>
            <?=$row['title']?> - <?=$row['author']?><br>
	    <iframe referrerpolicy="no-referrer" src="<?=$row['pic']?>" style="height:200px;width:100%;" frameBorder="0"></iframe>
            <form action='./bilibili2link.php'>
                <input type="hidden" name="url" value="<?=$row['arcurl']?>">
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
}
?>



