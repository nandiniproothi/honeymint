<?php

  if(isset($_POST["submit"])){
    $filename = $_POST["text"];
  }

$a = "https://raw.githubusercontent.com/uhimc00l/honeymint/master/" . $filename;
$b = "?token=ALD5GBY4UKC7DQWIKNTWP6C6W4XGO";

$final = $a . $b;
?>

<html>
<head>
  <title>Your Website Title</title>
    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
<meta property="og:url"                content=<?php echo "{$final}"; ?> />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="When Great Minds Don’t Think Alike" />
<meta property="og:description"        content="How much does culture influence creative thinking?" />
<meta property="og:image"              content="/Users/nandiniproothi/honeymint/jerm1.jpg" />
</head>
<body>
  <form method = "post">
    <input type = "text" name="text" placeholde="Type the name of your file (with extension)">
    <input type ="submit" name="submit" value="Click and then share!">
</form>
  <!-- Load Facebook SDK for JavaScript -->
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <!-- Your share button code -->
  <div class="fb-share-button" 
    data-href=<?php echo "{$final}"; ?>
    data-layout="button_count">
  </div>

</body>
</html>