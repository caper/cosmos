<?php

Plugin_Menu::add('main-menu', array(
   new Plugin_Menu_Item(array('code' => 'main', 'title' => 'Главная', 'uri' => '/', 'class' => null)),
   new Plugin_Menu_Item(array('code' => 'why', 'title' => 'Почему мы', 'uri' => '/about/why/', 'class' => null)),
   new Plugin_Menu_Item(array('code' => 'products', 'title' => 'Продукты', 'uri' => '/about/products/', 'class' => null)),
   new Plugin_Menu_Item(array('code' => 'cosmoslive', 'title' => 'Жизнь в стиле космос', 'uri' => '/about/cosmoslive/', 'class' => null)),
   //new Plugin_Menu_Item(array('code' => 'sa_payd', 'title' => 'Платежи', 'uri' => '/superadmin/', 'class' => null)),
   new Plugin_Menu_Item(array('code' => 'sa_wm', 'title' => 'Заявки на вывод', 'uri' => '/superadmin/accountant/', 'class' => null)),
   new Plugin_Menu_Item(array('code' => 'permit_documents', 'title' => 'Подтверждение пользователей', 'uri' => '/superadmin/permitDocument/', 'class' => null)),
 
));

$user = Model_User::getUser();
if(!$user || $user->subdomen != 'first') {
   // Plugin_Menu::hideItem('main-menu', array('sa_payd', 'sa_wm'));
    Plugin_Menu::hideItem('main-menu', array( 'permit_documents','sa_wm'));
}