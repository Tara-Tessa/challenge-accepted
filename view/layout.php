<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://use.typekit.net/vsa1qja.css">
  <title>Challenge Accepted</title>
</head>

<body>
  <div class="container">
    <header class="header">
      <a class="logo" href="index.php"><span class="hidden">logo</span></a>
      <div class="header_buttons">
        <a class="button_blue" href="index.php?page=tips"><span>lees tips</span></a>
        <a class="button_red" href="index.php?page=toevoegen"><span>voeg tip toe</span></a>
      </div>
    </header>
    <?php echo $content; ?>
  </div>
  <footer class="footer">
    <div class="footer_content">
      <p class="text text-footer">Help anderen er ook jonger uit zien en deel deze pagina! Zo kunnen jij en je vriendinnen samen naar de club!</p>
      <div class="social_media">
        <a class="facebook" href="#"><span class="hidden">Facebook</span></a>
        <a class="instagram" href="#"><span class="hidden">Instagram</span></a>
        <a class="twitter" href="#"><span class="hidden">Twitter</span></a>
        <a class="snapchat" href="#"><span class="hidden">Snapchat</span></a>
      </div>
      <a class="button_red" href="index.php?page=toevoegen"><span>voeg tip toe</span></a>
    </div>
  </footer>
  <script src="js/script.js"></script>
  <script src="js/validate.js"></script>
</body>

</html>