<?php

namespace TpGenerator\Command;

use RuntimeException;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Db;

define("APPLICATION_PATH", root_path());

class GeneratorTask extends Command
{
    protected $targetList = [];

    protected function configure()
    {
        // 指令配置
        $this->setName('generator')
          ->addOption('table', 't', Option::VALUE_REQUIRED, '表名')
          ->addOption('business', 'b', Option::VALUE_REQUIRED, '业务')
          ->addOption('module', 'm', Option::VALUE_OPTIONAL, '模块名称')
          ->addOption('tablePrefix', 'e', Option::VALUE_REQUIRED, '表前缀')
          ->addOption('select', 's', Option::VALUE_OPTIONAL, '选择生成选项,默认全部，或者组合 Enum,Model,Validate,Dao,Vo,Rsp,Bo,Repository,Controller')

          //      ->addOption("table", "-t")
          ->setDescription('the generator template command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('app\command\generatortask');

        $table = $input->getOption('table');
        $business = $input->getOption('business');
        $module = $input->getOption('module');
        $tablePrefix = $input->getOption('tablePrefix');
        $select = $input->getOption('select');
        $this->mainAction($module, $business, $table, $tablePrefix, $select ?: '');
    }

    public function getBusinessPath($isNameSpace, $module, $business, $layer)
    {
        $namespaceSep = $isNameSpace ? "\\" : "/";
        if ($layer == "model") {
            return "app{$namespaceSep}common{$namespaceSep}model{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "ModelEnums") {
            return "app{$namespaceSep}common{$namespaceSep}ModelEnums{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "dao") {
            return "app{$namespaceSep}common{$namespaceSep}dao{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "dto") {
            return "app{$namespaceSep}common{$namespaceSep}{$layer}{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "controller") {
            return "app{$namespaceSep}{$layer}{$namespaceSep}{$module}{$namespaceSep}$business";
        }
        if ($layer == "vo") {
            return "app{$namespaceSep}common{$namespaceSep}{$layer}{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "response") {
            return "app{$namespaceSep}common{$namespaceSep}{$layer}{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "bo") {
            return "app{$namespaceSep}common{$namespaceSep}{$layer}{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "repositories") {
            return "app{$namespaceSep}common{$namespaceSep}{$layer}{$namespaceSep}$module{$namespaceSep}$business";
        }
        if ($layer == "validate") {
            return "app{$namespaceSep}validate{$namespaceSep}$module{$namespaceSep}$business";
        }
        throw new RuntimeException("getBusinessPath 没有 " . $layer);
    }

    public function needGen($select, $file)
    {
        if (!$select) {
            return true;
        }
        return strpos($select, $file) > -1;
    }

    public function mainAction($module, $business, $table, $tablePrefix, $select = '')
    {
        $tableModule = ucfirst($this->toCamelCase($table));


        $tableName = $tablePrefix . $table;

        $fieldsInfo = \think\facade\Db::query("SHOW full COLUMNS FROM {$tablePrefix}{$table} ");
        array_walk($fieldsInfo, function (&$fieldInfo) {
            if ($fieldInfo['Default'] == 'CURRENT_TIMESTAMP') {
                $fieldInfo['Default'] = "";
            }
            $fieldInfo['isInt'] = strpos($fieldInfo['Type'], 'int') !== false;
        });
        $pkArr = array_filter($fieldsInfo, function ($item) {
            return $item['Key'] == 'PRI';
        });
        if ($pkArr == null) {
            throw new RuntimeException("找不到主键！");
        }

        $pk = $pkArr[0];

        //        $content = $this->render("ListBo.php", ["serviceName" => $tableModule, 'daoName' => $tableModule, "namespace" => $namespace, 'fieldsInfo' => $fieldsInfo]);
        //        $this->write(APPLICATION_PATH . "{$basePath}/contract/bo/{$tableModule}ListBo.php", $content);

        //        $content = $this->render("Service.php", ["serviceName" => $tableModule, 'daoName' => $tableModule, "namespace" => $namespace, 'modelName' => $tableModule]);
        //        $this->write(APPLICATION_PATH . "{$basePath}/impl/service/{$tableModule}Service.php", $content);


        //        $content = $this->render("IDao.php", ["serviceName" => $tableModule, 'daoName' => $tableModule, "namespace" => $namespace]);
        //        $this->write(APPLICATION_PATH . "{$basePath}/contract/dao/I{$tableModule}Dao.php", $content);


        $namespaceModelEnum = $this->getBusinessPath(true, $module, $business, "ModelEnums");
        $businessPath = $this->getBusinessPath(false, $module, $business, "ModelEnums");

        $content = $this->render("ModelEnum.php", [
          "serviceName" => $tableModule,
          'daoName' => $tableModule,
          'modelName' => $tableModule,
          'fieldsInfo' => $fieldsInfo,
          "namespace" => $namespaceModelEnum,
          'pk' => $pk,
          'tableName' => $tableName
        ]);
        //        echo APPLICATION_PATH . "{$businessPath}/{$tableModule}Enum.php";exit;

        $this->write(APPLICATION_PATH . "{$businessPath}/{$tableModule}Enum.php", $content, $select, 'Enum');

        $namespaceModel = $this->getBusinessPath(true, $module, $business, "model");
        $businessPath = $this->getBusinessPath(false, $module, $business, "model");
        $content = $this->render("Model.php", [
          "serviceName" => $tableModule,
          'daoName' => $tableModule,
          'tableModule' => $tableModule,
          'modelName' => $tableModule,
          'fieldsInfo' => $fieldsInfo,
          "namespace" => $namespaceModel,
          "namespaceModelEnum" => $namespaceModelEnum,
          'pk' => $pk,
          'tableName' => $table
        ]);

        $this->write(APPLICATION_PATH . "{$businessPath}/{$tableModule}.php", $content, $select, 'Model');


        $businessPath = $this->getBusinessPath(false, $module, $business, "validate");
        $namespaceValidate = $this->getBusinessPath(true, $module, $business, "validate");
        $content = $this->render("WebReq.php", ["reqName" => "Edit" . $tableModule, 'fieldsInfo' => $fieldsInfo, 'tableModule' => $tableModule, "namespace" => $namespaceValidate, 'pk' => $pk]);
        $this->write(APPLICATION_PATH . "{$businessPath}/Edit{$tableModule}Validate.php", $content, $select, 'Validate');

        $namespaceDao = $this->getBusinessPath(true, $module, $business, "dao");
        $businessPath = $this->getBusinessPath(false, $module, $business, "dao");
        $content = $this->render("Dao.php", [
          "serviceName" => $tableModule,
          'daoName' => $tableModule,
          'modelName' => $tableModule,
          'modelNameSpace' => $namespaceModel,
          "namespace" => $namespaceDao

        ]);
        $this->write(APPLICATION_PATH . "{$businessPath}/{$tableModule}Dao.php", $content, $select, 'Dao');


        $namespaceVo = $this->getBusinessPath(true, $module, $business, "vo");
        $businessPath = $this->getBusinessPath(false, $module, $business, "vo");
        $dtoName = "Search{$tableModule}Vo";
        $content = $this->render("Vo.php", ["serviceName" => $tableModule, 'dtoName' => $dtoName, "namespace" => $namespaceVo, 'fieldsInfo' => $fieldsInfo]);
        $this->write(APPLICATION_PATH . "{$businessPath}/{$dtoName}.php", $content, $select, 'Vo');

      $namespaceVo = $this->getBusinessPath(true, $module, $business, "vo");
      $businessPath = $this->getBusinessPath(false, $module, $business, "vo");
      $dtoName = "Edit{$tableModule}Vo";
      $content = $this->render("Vo.php", ["serviceName" => $tableModule, 'dtoName' => $dtoName, "namespace" => $namespaceVo, 'fieldsInfo' => $fieldsInfo]);
      $this->write(APPLICATION_PATH . "{$businessPath}/{$dtoName}.php", $content, $select, 'Vo');


      $namespaceRsp = $this->getBusinessPath(true, $module, $business, "response");
        $businessPath = $this->getBusinessPath(false, $module, $business, "response");
        $content = $this->render("Response.php", ["serviceName" => $tableModule, 'daoName' => $tableModule, "namespace" => $namespaceRsp, 'fieldsInfo' => $fieldsInfo]);
        $this->write(APPLICATION_PATH . "{$businessPath}/{$tableModule}Rsp.php", $content, $select, 'Rsp');

        $namespaceBo = $this->getBusinessPath(true, $module, $business, "bo");
        $businessPath = $this->getBusinessPath(false, $module, $business, "bo");
        $content = $this->render("Bo.php", ["serviceName" => $tableModule, 'daoName' => $tableModule, "namespace" => $namespaceBo, 'fieldsInfo' => $fieldsInfo]);
        $this->write(APPLICATION_PATH . "{$businessPath}/{$tableModule}Bo.php", $content, $select, 'Bo');


        $namespaceRepositories = $this->getBusinessPath(true, $module, $business, "repositories");
        $businessPath = $this->getBusinessPath(false, $module, $business, "repositories");
        $content = $this->render("Repository.php", [
          "serviceName" => $tableModule,
          'daoName' => $tableModule,
          'namespaceModel' => $namespaceModel,
          "namespace" => $namespaceRepositories,
          'fieldsInfo' => $fieldsInfo,
          'namespaceRsp' => $namespaceRsp,
          'namespaceBo' => $namespaceBo,
          'namespaceDao' => $namespaceDao,
          'namespaceVo' => $namespaceVo,
        ]);
        $this->write(APPLICATION_PATH . "{$businessPath}/{$tableModule}Repository.php", $content, $select, 'Repository');


        $namespaceController = $this->getBusinessPath(true, $module, $business, "controller");
        $businessPath = $this->getBusinessPath(false, $module, $business, "controller");
        $content = $this->render("Controller.php", [
          "serviceName" => $tableModule,
          'daoName' => $tableModule,
          "namespace" => $namespaceController,
          'fieldsInfo' => $fieldsInfo,
          'modelName' => $tableModule,
          'tableModule' => $tableModule,
          'namespaceVo' => $namespaceVo,
          'namespaceRsp' => $namespaceRsp,
          'namespaceBo' => $namespaceBo,

          'namespaceRepositories' => $namespaceRepositories,
          'namespaceValidate' => $namespaceValidate
        ]);
        $this->write(APPLICATION_PATH . "{$businessPath}/{$tableModule}.php", $content, $select, 'Controller');
    }

    public function getIdeHepler()
    {
        $arrayService = [];
        foreach ($this->di->getServices() as $service) {
            try {
                $serviceObject = $service->resolve();
                if (is_object($serviceObject)) {
                    $className = get_class($serviceObject);
                    $reflectionClass = new ReflectionClass($className);
                    $interface = $reflectionClass->getInterfaceNames();
                    if ($interface) {
                        $useName = $interface[0];
                    } else {
                        $useName = $className;
                    }
                    $array = explode("/", $useName);
                    $returnName = array_pop($array);
                    $arrayService[$returnName] = [
                      "actionName" => "get" . ucfirst($service->getName()),
                      "useName" => $useName,
                      "returnName" => $returnName,
                    ];
                }
            } catch (Exception $e) {
                //                echo $e->getMessage();
            }
        }
        return $arrayService;
    }

    //驼峰命名转下划线命名
    public function toUnderScore($str): string
    {
        $dst = preg_replace_callback('/([A-Z]+)/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return trim(preg_replace('/_{2,}/', '_', $dst), '_');
    }

    //下划线命名到驼峰命名
    public function toCamelCase($str)
    {
        $array = explode('_', $str);
        $result = $array[0];
        $len = count($array);
        if ($len > 1) {
            for ($i = 1; $i < $len; $i++) {
                $result .= ucfirst($array[$i]);
            }
        }
        return $result;
    }

    public function render($template, $data)
    {

        $tempatePath = APPLICATION_PATH . "/sd/smk/generator/Templates/";
        extract($data);
        ob_start();
        include $tempatePath . $template;
        $data = ob_get_contents();
        ob_clean();
        return $data;
    }


    public function write($dirFile, $content, $select = "", $pageRole = '')
    {
        $roles = 'Enum,Model,Validate,Dao,Vo,Rsp,Bo,Repository,Controller';

        echo "make " . realpath($dirFile) . "\r\n";

        $canWrite = true;
        if ($select) {
            if (strpos($roles, $pageRole) > -1) {
                $dir = dirname($dirFile);
                @mkdir($dir, 0777, true);
                file_put_contents($dirFile, $content);
            }
        } else {
            $dir = dirname($dirFile);
            @mkdir($dir, 0777, true);
            file_put_contents($dirFile, $content);
        }

    }

    public function ctrlAction($argv)
    {
        $module = $argv[1];
        $basePath = $argv[2];
        $tableName = $argv[3];

        $content = $this->render("Module.php", ["serviceName" => $module, 'daoName' => $module]);
        foreach ($this->moduels as $k => $v) {
            @mkdir(APPLICATION_PATH . "{$basePath}/Modules/$module/" . $v, 0777, true);
        }
        $this->write(APPLICATION_PATH . "{$basePath}/Modules/$module/Module.php", $content);
    }


    public function renderHelp($template, $data)
    {
        $tempatePath = APPLICATION_PATH . "/framework/Generator/Templates/";
        extract($data);
        ob_start();
        include $tempatePath . $template;
        $data = ob_get_contents();
        ob_clean();
        return $data;
    }

    public function ideHelperAction($argv)
    {
        $ideHepler = $this->getIdeHepler();
        $content = $this->render("IdeHelper.php", ["services" => $ideHepler]);
        $this->write(APPLICATION_PATH . ".idehelper.php", $content);
    }

    public function parseParams($argv)
    {
        $params = [];
        $data = array_reduce($argv, function ($last, $v) {
            $explode = explode("=", $v);
            $last[$explode[0]] = $explode[1];
            return $last;
        }, $params);
        return $data;
    }

    public function getByKey($array, $key, $default)
    {
        return $array[$key] ? $array[$key] : $default;
    }

    public function arrayUniqCheck($ar)
    {
        $uniq = array();
        foreach ($ar as $value) {
            $uniq[strtolower($value)] = $value;
        }
        return array_values($uniq);
    }

    public function autoDIAction($argv)
    {
        $params = $this->parseParams($argv);
        $appWritePath = $this->getByKey($params, "appToPath", "app");
        $moduleWritePath = $this->getByKey($params, "moduleToPath", "app/Modules");
        $fileName = $this->getByKey($params, "fileName", "AutoDiProvider.php");
        $dryRun = $this->getByKey($params, "dryRun", false);
        $modulePath = $this->getByKey($params, "modulePath", "app/Modules");
        $models = $this->getByKey($params, "models", []);
        $glob = glob("app/Model/*.php");
        foreach ($glob as $file) {
            $models[] = basename($file, ".php");
        }
        $services = $models;
        $glob = glob("app/Service/*.php");
        foreach ($glob as $file) {
            $services[] = basename($file, "Service.php");
        }
        $appDiContent = $this->renderHelp("AutoDiProvider.php", ["namespace" => ucfirst("app"), "models" => $models, "services" => array_unique($services), 'className' => basename($fileName, ".php")]);

        $waitWrite = [["path" => $appWritePath, "fileName" => $fileName, "content" => $appDiContent]];

        $aModule = $this->getByKey($params, "module", "");
        if ($aModule) {
            $globModules[] = $aModule;
        } else {
            $globModules = scandir($modulePath);
        }
        foreach ($globModules as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }
            $moduleFileName = $module . $fileName;
            $moduleModels = glob(join(DIRECTORY_SEPARATOR, [$modulePath, $module, "Model", "*.php"]));
            $models = [];
            $services = [];
            foreach ($moduleModels as $file) {
                $models[] = basename($file, ".php");
            }
            $moduleNameSpacePrefix = str_replace("/", "/", $modulePath);
            $namespace = join("/", [$moduleNameSpacePrefix, $module]);

            $services = $models;
            $glob = glob(join(DIRECTORY_SEPARATOR, [$modulePath, $module, "Service", "*Service.php"]));
            foreach ($glob as $file) {
                $services[] = basename($file, "Service.php");
            }


            $arrayUniqCheck = $this->arrayUniqCheck($services);
            $arrayUniq = array_unique($services);
            $diff = array_diff($arrayUniq, $arrayUniqCheck);
            if ($diff) {
                echo "请检查model,和service的大小写拼写，异常模块";
                var_dump($diff, $arrayUniq, $arrayUniqCheck);
                exit;
            }
            $appDiContent = $this->renderHelp("AutoDiProvider.php", [
              "namespace" => ucfirst($namespace), "models" => array_unique($models),
              'className' => basename($moduleFileName, ".php"),
              "services" => $arrayUniqCheck
            ]);
            $waitWrite[] = ["path" => join(DIRECTORY_SEPARATOR, [$moduleWritePath, $module]), "fileName" => $moduleFileName, "content" => $appDiContent];
        }

        if ($dryRun) {
            foreach ($waitWrite as $value) {
                $file = $value["path"] . DIRECTORY_SEPARATOR . $value['fileName'];
                echo "writeFile {$file} \r\n";
                echo "writeContent \r\n{$value["content"]}\r\n";
            }
        } else {
            $loader = [];
            foreach ($waitWrite as $value) {
                @mkdir($value['path'], 655, true);
                $file = $value["path"] . DIRECTORY_SEPARATOR . $value['fileName'];
                echo "writeFile  {$file} \r\n";
                file_put_contents($file, $value['content']);
            }
        }
        $updateIdeHelper = $this->getByKey($params, "updateIdeHelper", false);
        if ($updateIdeHelper) {
            $this->ideHelperAction($argv);
        }
    }

    public static function parseComment($field): ?array
    {
        $data = null;
        if (strpos($field['Comment'], "#") > -1 || strpos($field['Comment'], "@") > -1 || strpos($field['Comment'], "=") > -1) {
            $statusInfo = explode("#", $field['Comment']);
            $data = [];
            if (count($statusInfo) >= 2) {
                $info = [
                  "comment" => $statusInfo[0],
                  "name" => $field['Field'],
                  "items" => []
                ];
                array_shift($statusInfo);

                foreach ($statusInfo as $item) {

                    $statusItemData = explode("@", $item);
                    if (count($statusItemData) == 2) {
                        $status = explode('=', $statusItemData[1]);
                        $info['items'][] = [
                          "comment" => $statusItemData[0],
                          "key" => $status[0],
                          "value" => $status[1],
                        ];
                    }

                }
                $data = $info;
            }
        }
        return $data;
    }
}
