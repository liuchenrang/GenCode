<?php echo "<?php\r\n" ?>
namespace Phalcon;

<?php
foreach ($services as $value) {
    echo "use {$value['useName']};\r\n";
}
?>
<?php echo "/**\r\n" ?>
<?php echo " * Interface \Phalcon\DiInterface\r\n" ?>

<?php echo " * @package  Phalcon\DiInterface\r\n" ?>
<?php
foreach ($services as $value) {
    echo " *  @method {$value['returnName']} {$value['actionName']} ()\r\n";
}
?>
<?php echo " */\r\n" ?>

interface DiInterface{

}