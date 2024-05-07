<?php echo "<?php\r\n" ?>

// +----------------------------------------------------------------------
// | 桑豆
// +----------------------------------------------------------------------




namespace <?php echo $namespace; ?>;

use <?php echo $modelNameSpace; ?>\<?php echo $modelName; ?>;
use app\common\dao\BaseDao;
use app\common\model\BaseModel;
use think\db\Query;
class <?php echo $modelName; ?>Dao extends BaseDao
{

    /**
     * @return BaseModel
     */
    protected function getModel(): string
    {
        return <?php echo $modelName; ?>::class;
    }
    public function query(): <?php echo $modelName."\r\n"; ?>
    {
        return new <?php echo $modelName; ?>();
    }
    /**
    * 基本查询器
    *
    * @return Query
    */
    public function baseQuery(): Query
    {
        return $this->query()->where('is_del', 0)->append(<?php echo $modelName; ?>::getAppend());
    }
}
