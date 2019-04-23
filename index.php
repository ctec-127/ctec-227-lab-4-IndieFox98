<?php
    # Name: index.php
    # Description: Image Gallery Home Page for Lab 4

    # directory to store uploaded images
    $upload_dir = 'imgloads';

    $upload_errors = [
        UPLOAD_ERR_INI_SIZE => "File size is too large! Try again.",
        UPLOAD_ERR_FORM_SIZE => "File size is too large! Try again.",
        UPLOAD_ERR_PARTIAL => "File has apparently been partially uploaded. Try again.",
        UPLOAD_ERR_NO_FILE => "Could you please be a dear and select a file to upload? Thanks.",
        UPLOAD_ERR_NO_TMP_DIR => "Stupid programmer error: No .tmp folder.",
        UPLOAD_ERR_CANT_WRITE => "File cannot be written to the disk. Try again.",
        UPLOAD_ERR_EXTENSION => "Dammit! File has been stopped by a PHP extension!"
    ]; # I should work on the Linux kernel one day

    # Check if form gets posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tmp_file = $_FILES['image_file']['tmp_name'];

        $target_file = basename($_FILES['image_file']['name']);

        $file_ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));

        if (($file_ext == 'png' || $file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'gif') || empty($tmp_file)) {
            if (move_uploaded_file($tmp_file, $upload_dir . '/' . $target_file)) { # Check if the selected image has been moved to the destination folder
                $msg = "Upload successful!";
                $msg_class = "success";
            } else {
                $err = $_FILES['image_file']['error'];
                $msg = $upload_errors[$err];
                $msg_class = "alert";
            }
        } else {
            $msg = "Only png, jpg/jpeg, and gif files are allowed. This is an <strong>image</strong> uploader, genius.";
            $msg_class = "alert";
        }
        
    }

    # Use GET method for deleting images
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['del'])) {
            if (file_exists("{$_GET['del']}")) {
                unlink($_GET['del']);
                $msg = "Image deleted. :(";
                $msg_class = "success";
            } else {
                $msg = "Image to be deleted does not even exist, like your observation skills.";
                $msg_class = "alert";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet"> 
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/fade.js"></script>
    <title>imgload - the latest image uploader</title>
</head>
<body>
    <header>
        <h1>imgload - the latest image uploader</h1>
    </header>
    <main>
        <?php if (!empty($msg)) {echo "<p id='msg' class='{$msg_class}'>{$msg}</p>";} ?>
        <section id="cta"> <!-- for "call-to-action" -->
            <h2>Uploading images has never been more satisfying. Just simply Browse to select an image file, then Upload it!</h2>
            <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
                <input type="file" name="image_file">
                <input type="submit" value="Upload">
            </form>
        </section>
        <?php
            if (count(scandir($upload_dir)) > 2) {
                foreach (scandir($upload_dir) as $image) {
                    if (!is_dir($image)) {
                        echo "<div class='image'>";
                        echo "<img src='{$upload_dir}/{$image}' alt='{$image}'>";
                        echo "<p class='filename'>{$image}</p>";
                        echo "<a href='?del={$upload_dir}/{$image}' class='delete'>Delete</a>";
                        echo "</div>";
                    }
                }
            } else {
                echo "<p>No images have been uploaded. Don't be shy, rookie!</p>";
            }
        ?>
    </main>
</body>
</html>