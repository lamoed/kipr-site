<?php
require_once 'config/Config.php';
require_once 'base/Core.php';

if($config['show_errors']) {
    @ini_set("display_errors", 1);
} else {
    @ini_set("display_errors", 0);
}
if(ini_get('short_open_tag') == 'Off' || ini_get('short_open_tag') == false) {
    @ini_set('short_open_tag', 'On');
}
// Ğåãèñòğàöèÿ áàçîâîãî àâòîçàãğóç÷èêà íîâûõ êëàññîâ
spl_autoload_register('Core::load');
$start = new Core($config);
$start->run();