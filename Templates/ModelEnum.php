<?php echo "<?php\r\n"; ?>
/**
* +----------------------------------------------------------------------
* | 桑豆
* +----------------------------------------------------------------------
*/
declare(strict_types=1);

namespace <?php echo $namespace; ?>;


use app\common\ModelEnums\BaseEnum;

class <?php echo $modelName; ?>Enum extends BaseEnum
{
<?php
//状态格式   删除状态#通过@pass=1#未通过@deny=2

foreach ($fieldsInfo as $field) {
    if (strpos($field['Comment'], "#") > -1||strpos($field['Comment'], "@") > -1||strpos($field['Comment'], "=") > -1) {
        $statusInfo = explode("#", $field['Comment']);
        if (count($statusInfo) >= 2) {
            echo '    //' . $statusInfo[0] . "\r\n";
            array_shift($statusInfo);
            foreach ($statusInfo as $item) {
                $statusItemData = explode("@", $item);
                if (count($statusItemData) == 2) {
                    echo "    //" . $statusItemData[0] . "\r\n";
                    $status = explode('=', $statusItemData[1]);
                    $enumItem = strtoupper($field['Field']) . "_" . strtoupper($status[0]) . '=' .( is_numeric($status[1]) ?$status[1]:"'{$status[1]}'" . "");
                    echo "    public const {$enumItem};\r\n";

                }

            }
        }
    }
}
foreach ($fieldsInfo as $field) {
  if (strpos($field['Comment'], "#") > -1 || strpos($field['Comment'], "@") > -1 || strpos($field['Comment'], "=") > -1) {
    $statusInfo = explode("#", $field['Comment']);
    if (count($statusInfo) >= 2) {
      echo '//' . $statusInfo[0] . "\r\n";
      array_shift($statusInfo);
      $fieldName = ucfirst(\app\common\Helper::toCamelCase($field['Field']));

      echo "    public static function get{$fieldName}Map()\r\n    {";
      echo "    \r\n        return  [\r\n";
      foreach ($statusInfo as $item) {
        $statusItemData = explode("@", $item);
        if (count($statusItemData) == 2) {
          $status = explode('=', $statusItemData[1]);
          $constKey = strtoupper($field['Field']) . "_" . strtoupper($status[0]);
          echo "            self::$constKey=>'$statusItemData[0]',\r\n";
        }

      }
    }
    echo "        ];\r\n";
    echo "    }\r\n";
  }
}
?>


}
