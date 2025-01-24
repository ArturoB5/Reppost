<?php
$id = intval($_GET['id']);
$postURL = 'http://localhost/Reppost/home.php?id=' . $id;
?>
<!DOCTYPE html>
<html>

<head>
    <title>Compartir Post</title>
    <link rel="stylesheet" href="View/css/bootstrap.min.css">
</head>

<body>
    <div class="container" style="margin-top: 30px;">
        <h4>Elige dónde compartir</h4>
        <a class="btn btn-primary"
            href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($postURL); ?>"
            target="_blank">Facebook</a>
        <a class="btn btn-info"
            href="https://twitter.com/intent/tweet?url=<?php echo urlencode($postURL); ?>&text=Visita+este+post"
            target="_blank">Twitter</a>
        <a class="btn btn-success"
            href="https://api.whatsapp.com/send?text=<?php echo urlencode('Mira este post: ' . $postURL); ?>"
            target="_blank">WhatsApp</a>
    </div>
</body>

</html>