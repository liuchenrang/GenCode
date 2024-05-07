<?php echo "<?php\r\n" ?>



namespace <?php echo $namespace;?>\contract\bo;


use app\app1\contract\entity\UserEntity;

use <?php echo $namespace;?>\contract\entity\<?php echo $daoName;?>Entity;

use sd\common\framework\bo\PageBo;
use sd\common\framework\JsonObject;
use think\Collection;
use think\contract\Arrayable;

/**
 * @method array<<?php echo $daoName;?>Entity> get<?php echo $daoName;?>List()
 */
class <?php echo $daoName;?>ListBo extends PageBo{


}
