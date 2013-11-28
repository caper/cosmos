<?

/**
* Menu
*/
class Model_Menu extends Mikron_Model {

    public static function show_menu($menu_number) {
        $sql = "SELECT id, text from menu where place={$menu_number} AND parent = 0";
        $result = db1::query($sql);
        while ($menu = mysql_fetch_assoc($result)) { 
            if ($menu_number == 0) {
                ?>
                <li> <span><?=$menu['text']?></span>
                <ul  class="subsubmenu">
                <?
            } else {
                ?>
                <li> <a href="/page/<?=$menu['id']?>/"><span><?=$menu['text']?></span></a>
                <ul  class="subsubmenu">
                <?
            }
            $sql2 = "SELECT text,id from menu where parent={$menu['id']}";
            $result2 = db1::query($sql2);
            while($submenu = mysql_fetch_assoc($result2)) {
                print "<a href='/page/{$submenu['id']}'/> <li>{$submenu['text']} </li></a>";
            }
            print "</ul></li>";
        }
    }

}
