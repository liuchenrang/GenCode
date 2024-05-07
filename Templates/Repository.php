<?php echo "<?php\r\n" ?>

/**
 * +----------------------------------------------------------------------
 * | 桑豆
 * +----------------------------------------------------------------------
 */

namespace <?php echo $namespace; ?>;

use <?php echo $namespaceVo; ?>\Search<?php echo $daoName; ?>Vo;
use <?php echo $namespaceBo; ?>\<?php echo $daoName; ?>Bo;
use <?php echo $namespaceDao; ?>\<?php echo $daoName;?>Dao;
use <?php echo $namespaceRsp; ?>\<?php echo $daoName;?>Rsp;
use <?php echo $namespaceModel; ?>\<?php echo $daoName;?>;
use app\common\repositories\BaseRepository;
use think\exception\ValidateException;
use think\Collection;

/**
  * @method delete(int $id) 按照主键删除
  * @method <?php echo $daoName;?> create(array $data) 创建
  * @method update(int $id, array $data) 更新
  * @method updateByWhere(array $where, array $data) 条件更新
  * @method getValue(array $where, string $value) 获取字段值
  * @method <?php echo $daoName;?> get(int $id) 获取单条记录
  * @method existsWhere(array $where) 判断
  * @method countWhere(array $where)  统计
  * @method <?php echo $daoName;?> getWhere(array $where, string $field = '*', array $with = []) 获取单条记录
  * @method Collection<<?php echo $daoName;?>> selectWhere(array $where, string $field = '*', string $order = '', array $with = []) 获取所有记录
  */

class <?php echo $daoName;?>Repository extends BaseRepository
{

    public function __construct(<?php echo $daoName;?>Dao $dao)
    {
      $this->dao = $dao;
    }
    public function getSearchList(Search<?php echo $daoName;?>Vo $where, int $page, int $limit): array
    {
        $query = $this->dao->baseQuery();

        //以上写业务查询逻辑
        $count = $query->count();
        $query = $this->withPage($query, $page, $limit);
        $list = $query->order(<?php echo $daoName;?>::tablePk()." desc")->select()->map(function (<?php echo $daoName;?> $item){
            return  <?php echo $daoName;?>Rsp::strictBuild($item->toArray());
        });
        return compact('count', 'list');
    }
    public function edit($id,<?php echo $daoName;?>Bo $data):<?php echo $daoName;?>Rsp
    {
        if ($id) {
            $this->update($id, $data->toArray());
            $model = $this->get($id);
        } else {
            $model = $this->create($data->toArray());
        }
        return <?php echo $daoName;?>Rsp::build($model->toArray());
    }


}
