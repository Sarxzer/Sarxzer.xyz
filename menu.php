<div class="navbar">
    <a href="/"><? echo translate('menu_home') ?></a>
    <div class="dropdown">
        <a href="/projects/" class="btn"><? echo translate('menu_projects') ?></a>
        <div class="content">
            <a href="/projects/free-fa" class="element"><? echo translate('menu_free_fa') ?></a>
            <a href="/projects/sarxzerxyz" class="element"><? echo translate('menu_sarxzerxyz') ?></a>
            <a href="/projects/minexpanded" class="element"><? echo translate('menu_minexpanded') ?></a>
            <a href="/projects/magic-datapack" class="element"><? echo translate('menu_magic_datapack') ?></a>
            <a href="/projects/fiverr" class="element"><? echo translate('menu_fiverr') ?></a>
        </div>
    </div>
    <a href="/contact"><? echo translate('menu_contact') ?></a>
    <a href="/blogs/"><? echo translate('menu_blogs') ?></a>
    <form class="lang" action="" method="post">
        <select name="lang" id="lang" onchange="this.form.submit()">
            <option value="en" <? echo $_SESSION['lang'] == 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
            <option value="fr" <? echo $_SESSION['lang'] == 'fr' ? 'selected' : '' ?>>🇫🇷 Français</option>
            <option value="llc" <? echo $_SESSION['lang'] == 'llc' ? 'selected' : '' ?>>😺 LOLcat</option>
            <option value="frmd" <? echo $_SESSION['lang'] == 'frmd' ? 'selected' : '' ?>>💩 Franmerde</option>
        </select>
    </form>

    <?

        if (isset($_SESSION['id'])) {

            include "src/php/db.php";

            $_tempQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
            $_tempQuery->execute(['id' => $_SESSION['id']]);
            $_temp = $_tempQuery->fetch();

            if (!isset($_isLoaded)) {
                include "src/php/notify.php";
            }

            if ($_temp['id'] == 26 || $_temp['id'] == 37) {
                echo '<a href="/other/hits">Baffe</a>';
            }

            if ($_temp['role'] !== 'admin') {
                echo '
                <a href="/account/notification" class="notification-button"><i class="fa-solid fa-bell"></i></a>
                <div class="dropdown account-button">
                    <a href="/account/user" class="btn">' . translate('menu_account') . '</a>
                    <div class="content">
                        <a href="/blogs/create" class="element">' . translate('menu_create_a_blog') . '</a>
                        <a href="/blogs/my-blogs" class="element">' . translate('menu_my_blogs') . '</a>
                        <a href="/account" class="element">' . translate('menu_settings') . '</a>
                        <a href="/account/logout" class="element">> Logout</a>
                    </div>
                </div>';
            } else if ($_temp['role'] == 'admin') {
                echo '
                <a href="/account/notification" class="notification-button">'. countUnreadNotifications($_SESSION['id'], $mysqlClient) . '  <i class="fa-solid fa-bell"></i></a>
                <div class="dropdown account-button">
                    <a href="/account/user" class="btn">' . translate('menu_account') . '</a>
                    <div class="content">
                        <a href="/blogs/create" class="element">' . translate('menu_create_a_blog') . '</a>
                        <a href="/blogs/my-blogs" class="element">' . translate('menu_my_blogs') . '</a>
                        <a href="/account" class="element">' . translate('menu_settings') . '</a>
                        <a href="/admin" class="element">' . translate('menu_admin') . '</a>
                        <a href="/account/logout" class="element">' . translate('menu_logout') . '</a>
                    </div>
                </div>';
            }

        } else {
            echo '<a href="/account/login"  class="account-button" >' . translate('menu_login_register') . '</a>';
        }

        $_temp = null;

    ?>
</div>