<?

/**
* Page
*/
class Model_Page extends Mikron_Model {

    public static function getById($id) {
        $id = (int)$id;
        $result = db1::fetch(db1::query("SELECT * from menu where id = {$id}"));
        return $result;
    }

}
