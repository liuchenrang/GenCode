<?php echo "<?php\r\n" ?>
namespace <?php echo $namespace; ?>;
/**
 * +----------------------------------------------------------------------
 * | 桑豆
 * +----------------------------------------------------------------------
 */

// | 桑豆
// +----------------------------------------------------------------------


use <?php echo $namespaceRepositories; ?>\<?php echo $modelName; ?>Repository;
use <?php echo $namespaceValidate; ?>\<?php echo $modelName; ?>\Edit<?php echo $tableModule; ?>Validate;
use <?php echo $namespaceVo; ?>\Search<?php echo $tableModule; ?>Vo;
use <?php echo $namespaceBo; ?>\<?php echo $daoName; ?>Bo;

use crmeb\basic\BaseController;
use app\Request;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 */
class  <?php echo $modelName; ?> extends BaseController
{

  protected $repository;

  /**
   * CouponIssue constructor.
   */
  public function __construct(App $app,  <?php echo $modelName; ?>Repository $repository)
  {
    parent::__construct($app);
    $this->repository = $repository;
  }

  /**
   * @throws DbException
   * @throws DataNotFoundException
   * @throws ModelNotFoundException
   */
  public function lst(Search<?php echo $modelName; ?>Vo $search)
  {
       [$page, $limit] = $this->getPage();
       return app('json')->success($this->repository->getSearchList($search, $page, $limit));
  }
  public function edit(Request $request )
  {
        $baseValidate = Edit<?php echo $tableModule; ?>Validate::factory();
        $baseValidate->getRuleKey();
        $params = $request->params($baseValidate->getRuleKey());
        $data = $baseValidate->checkIfFailThrowException($params);
        $bo = <?php echo $modelName; ?>Bo::build($data);
        $this->repository->edit($bo->id(), $bo);
        return app('json')->success('修改成功');
  }
  public function get($id)
  {
      $data = $this->repository->get($id);
      return app('json')->success($data);
  }
}
