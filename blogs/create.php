<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title><? echo translate('blogs_create_title'); ?> | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div class="blog-creation-zone">
        <form action="" method="post" id="create-blog">
            <div class="title">
                <label for="title"><? echo translate('blogs_create_zone_title'); ?></label>
                <input type="text" name="title" id="title" placeholder="<? echo translate('blogs_create_zone_title_ex'); ?>" required value="<? isset($_POST['title']) ? $_POST['title'] : '' ?>">
            </div>
            <div class="content">
                <label for="content"><? echo translate('blogs_create_zone_content'); ?></label>
                <textarea name="content" id="content" cols="30" rows="10" placeholder="<? echo translate('blogs_create_zone_content_ex'); ?>" required><? isset($_POST['content']) ? $_POST['content'] : '' ?></textarea>
            </div>
            <div class="category">
                <label for="lang"><? echo translate('blogs_create_zone_language')?></label>
                <select name="lang" id="lang">
                    <option value="en">English</option>
                    <option value="fr">Français</option>
                </select>

            </div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <div class="g-recaptcha" 
                 data-sitekey="6LfY-EMpAAAAAADFVa-VeFvEIAw-JjN-nclaTzIs" 
                 data-size="invisible" 
                 data-callback="onSubmit">
            </div>
            <p class="note"><? echo translate('blogs_create_note'); ?></p>
            <input class="submit" type="submit" value=<? echo translate('blogs_create_button'); ?> name="submit_blog">
        </form>
    </div>

    <?
        require '../src/php/db.php';
        if (isset($_SESSION['id'])) {
            if (isset($_POST['submit_blog'])) {
                if (!isset($_POST['g-recaptcha-response'])) {
                    echo translate('blogs_create_captcha_failed');
                    exit();
                }

                $recaptchaResponse = $_POST['g-recaptcha-response'];
                $recaptchaSecretKey = '6LfY-EMpAAAAADr0EM8agzCECoa1EKdT1DbRYDdm';

                $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse";
                $verify = json_decode(file_get_contents($recaptchaUrl));

                if (!$verify->success) {
                    echo translate('blogs_create_captcha_failed');
                    exit();
                }
                $title = $_POST['title'];
                $content = $_POST['content'];
                $blogLang = $_POST['lang'];

                if (empty($title) || empty($content)) {
                    echo translate('blogs_create_please_fill');
                } else if (strlen($title) < 5 || strlen($title) > 50) {
                    echo translate('blogs_create_title_must');
                } else if (strlen($content) < 10 || strlen($content) > 1000) {
                    echo translate('blogs_create_content_must');
                } else {
                    $insertQuery = $mysqlClient->prepare("INSERT INTO blogs (title, content, author_id, lang) VALUES (:title, :content, :author_id, :lang)");
                    $insertQuery->execute([
                        'title' => $title, 
                        'content' => $content, 
                        'author_id' => $_SESSION['id'],
                        'lang' => $blogLang
                    ]);
                    $idQuery = $mysqlClient->prepare("SELECT id FROM blogs WHERE title = :title");
                    $idQuery->execute(['title' => $title]);
                    $id = $idQuery->fetch()['id'];
                    header('Location: /blogs/blog?id=' . $id);
                }
            }
        } else { 
            echo translate('blogs_create_logged');
        }
    ?>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping("<? echo translate('blogs_create_page_title'); ?>", 100, title);
    </script>
    <script>
        function onSubmit(token) {
            // Get the form
            var form = document.getElementById("create-blog");
        
            // Create a new hidden input field
            var input = document.createElement("input");
        
            // Set the input field's name to "g-recaptcha-response"
            input.setAttribute("name", "submit_blog");
            input.setAttribute("value", "Create blog post 📝");
        
            // Add the input field to the form
            form.appendChild(input);
        
            // Submit the form
            form.submit();
        }

        document.getElementById('create-blog').addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.execute();
        });
    </script>
</body>
</html>
