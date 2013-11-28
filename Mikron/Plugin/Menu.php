<?php

class Plugin_Menu {

    private static $variables = array();
    private static $get_params = array();
    private static $hiddenItems = array();
    private static $disabledItems = array();
    private static $activeItems = array();

    private static $selectedItems = array();

    /**
    * Array of menus associating with layouts
    */
    protected static $_menus = array();

    public static function setVariable($name, $value) {
        self::$variables[$name] = $value;
    }


    /**
    * @author sciner
    * @since 24.07.2013
    */
    public static function getActiveItem($menu_id) {
        if(array_key_exists($menu_id, self::$selectedItems)) {
            return self::$selectedItems[$menu_id];
        }
    }

    /**
    *	Добавление GET параметров в генерируемые ссылки элементов меню
    */
	public static function addParam($params = array()) {
        foreach($params as $name => $value) {
        	self::$get_params[$name] = $value;
        }
    }

    /**
    * Добавление пунктов меню
    * 
    * @param string $menu_id
    * @param Plugin_Menu_Item[] $items
    */
    public static function add($menu_id, $items) {
        $items = Functions::castAll($items, 'Plugin_Menu_Item');
        self::$_menus[$menu_id] = $items;
        self::$hiddenItems[$menu_id] = array();
        self::$disabledItems[$menu_id] = array();
        self::$activeItems[$menu_id] = array();
    }

    /**
     * Скрытие одного или нескольких пунктов меню
     * 
     * @param string $menuID Идентификатор меню
     * @param mixed $itemCodes Строка или массив кодов скрываемых пунктов меню
     */
    public static function hideItem($menu_id, $item_codes) {
        if(!is_array($item_codes)) {
            $item_codes = array($item_codes);
        }
        foreach($item_codes as $code) {
            self::$hiddenItems[$menu_id][] = $code;
        }
    }

    public static function disableItem($menu_id, $item_codes) {
        if(!is_array($item_codes)) {
            $item_codes = array($item_codes);
        }
        foreach($item_codes as $code) {
            self::$disabledItems[$menu_id][] = $code;
        }
    }

    /**
    * Добавление одного или нескольких пунктов меню
    * 
    * @param string $menuID Идентификатор меню
    * @param string $code
    * @param string $title
    * @param string $uri
    * @param string $class
    * 
    * @return bool
    */
    public static function addItem($menu_id, $code, $title, $uri, $class) {
        self::$_menus[$menu_id][$code] = array('title' => $title, 'uri' => $uri, 'class' => $class);
        return true;
    }

    public static function setActive($menu_id, $code) {
        //if(!array_key_exists($menu_id, self::$_menus)) {
        //    return false;
        //}
        self::$activeItems[$menu_id][] = $code;
        return true;
    }

    public static function get($id) {
        return self::$_menus[$id];
    }

    public static function draw($id, $ul_class = null, $params = array()) {
        if(!array_key_exists($id, self::$_menus)) {
            return false;
        }
        self::recvDraw($id, self::$_menus[$id], $ul_class);
    }

    private static function recvDraw($id, $items, $ul_class = null) {
        $index = 0;
        $count = count($items);
        $hiddenItems = array_key_exists($id, self::$hiddenItems) ? self::$hiddenItems[$id] : array();
        $disabledItems = array_key_exists($id, self::$disabledItems) ? self::$disabledItems[$id] : array();
        $activeItems = array_key_exists($id, self::$activeItems) ? self::$activeItems[$id] : array();
        $activeItems = array_key_exists($id, self::$activeItems) ? self::$activeItems[$id] : array();
        ?>
           <ul id="<?=$id?>" <?if(!is_null($ul_class)){echo " class=\"{$ul_class}\"";}?>>
            <?foreach($items as $menu) {
                $menu = (object)$menu;
                if(in_array($menu->code, $hiddenItems)) {
                    continue;
                }
                $uri = $menu->uri;
                reset(self::$variables);
                foreach(self::$variables as $name => $value) {
                    $uri = str_replace('{$'.$name.'}', $value, $uri);
                    $menu->title = str_replace('{$'.$name.'}', $value, $menu->title);
                }
                foreach(self::$get_params as $name => $value) {
                	if(strpos($uri, '?')) {
                		$uri .= '&';
                	} else {
                		$uri .= '?';
                	}
                	$uri .= $name.'='.$value;
                }
                $class = $menu->class;
                if($index == 0) {$class .= ' first';} elseif ($index == $count-1) {$class .= ' last';}
                $index++;
                if(in_array($menu->code, $activeItems)) {
                    self::$selectedItems[$id] = $menu;
                    $class .= ' active';
                }
                if(in_array($menu->code, $disabledItems)) {
                    $class .= ' disabled';
                    ?><li class="<?=$class;?>"><a><?=$menu->title;?></a></li><?
                } else {
                    ob_start();
                    if(in_array($menu->code, $activeItems)) {
                        if(isset($menu->child) && is_array($menu->child)) {                            
                            self::recvDraw($id.'/'.$menu->code, $menu->child, null);
                        }
                    }
                    $submenu = ob_get_clean();
                    ?><li class="<?=$class;?>"><a href="<?=$uri;?>"><?=$menu->title;?></a><?=$submenu?></li><?
                }
             } ?>
           </ul>
        <?php
    }

}