<?php echo "<?php\r\n" ?>

<?php echo "namespace $namespace\\$tableModule;\r\n" ?>

use think\Validate;

use think\Request;
use sd\smk\common\validate\BaseValidate;

class <?php echo $reqName; ?>Validate extends BaseValidate
{


    protected $rule = [
    <?php
    $attrs = [];
    $ignore = ['is_del', 'create_time', 'update_time'];
    foreach ($fieldsInfo as $field) {
        if (array_search($field['Field'], $ignore)) {
            continue;
        }

        $comment = $field['Comment'] ? "" : '//';
        $isRequire = $field['Null'] == "YES" ? "" : 'require';

        if ($pk['Field'] == $field['Field']) {
            $isRequire = 'option';
        }
        $parseComment = null;
        $parseComment = \sd\smk\generator\command\GeneratorTask::parseComment($field);
        if ($parseComment) {
            $status = [];
            foreach ($parseComment['items'] as $item) {
                $status[] = $item['value'];
            }
            $in = implode(",", $status);
            if ($isRequire) {
                $attrs [] = "      $comment'{$field['Field']}|{$parseComment['comment']}' => '{$isRequire}|in:$in'";
            } else {
                $attrs [] = "   //   $comment'{$field['Field']}|{$parseComment['comment']}' => 'require|in:$in'";
            }

        } else {
            $comment = explode("#", $field['Comment']);
            $attrs [] = "      '{$field['Field']}|{$comment[0]}' => '{$isRequire}'";
        }

    }
    echo implode(",\r\n", $attrs);
    ?>

    ];

    protected $message = [
      <?php
      foreach ($fieldsInfo as $field) {
          $parseComment = \sd\smk\generator\command\GeneratorTask::parseComment($field);
          if ($parseComment) {
              echo "    '{$parseComment['name']}.in' => '{$parseComment['comment']}不能为空！',\r\n";
          }
      };
      ?>
    ];

}
