<?php
namespace dektrium\rbac\commands;

use yii\console\Controller;
use yii\rbac\Item;
use yii\helpers\VarDumper;
use dektrium\rbac\models\Role;
use dektrium\rbac\models\Permission;
use dektrium\rbac\models\Search;
use Yii;

class ItemController extends Controller {

  /**
   * @var string
   */
  protected $modelClass;
  private function guessType($type){
    if (strtolower($type) == 'role') $type=Item::TYPE_ROLE;
    elseif (strtolower($type) == 'permission') $type=Item::TYPE_PERMISSION;
    elseif (!in_array($type, [Item::TYPE_ROLE, Item::TYPE_PERMISSION])) {
      $this->stderr(Yii::t('rbac', "Invalid type detected. Fallback to Permission"));
      $type = Item::TYPE_PERMISSION;
    }
    return $type;
  }
  /**
   * Lists all created items.
   * @param $type   The auth item type: 1 = ROLE, 2 = PERMISSION, 'role' or 'permission'
   * @return string
   */
  public function actionIndex($type='permission')
  {
      $type=$this->guessType($type);
      $filterModel = new Search($type);
      $data = $filterModel->search([])->allModels;
      $this->stdout(Yii::t('nc', "The following items was found:\n===BEGIN===\n"));
      VarDumper::dump($data);
      $this->stdout("\n===END===\n");
  }

  /**
   * Shows create form.
   * @param
   * @return string
   */
  public function actionCreate($type, $name, $description=null, $rulename=null)
  {
      /** @var \dektrium\rbac\models\Role|\dektrium\rbac\models\Permission $model */
      $type = $this->guessType($type);
      if ($type == Item::TYPE_ROLE) {
        $this->modelClass = Role::className();
      } else {
        $this->modelClass = Permission::className();
      }
      $model = Yii::createObject([
          'class'    => $this->modelClass,
          'scenario' => 'create',
      ]);
      $model->setAttributes([
        'name' =>$name,
        'description' => $description,
        'rulename' => $rulename,
      ]);
      if ($model->save()) {
        $this->stdout(Yii::t('rbac', "Item has been created\n"));
      } else {
        $this->stderr(Yii::t('rbac', "Cannot create Item\n{errors}\n", ['errors' => VarDumper::dumpAsString($model->errors)]));
      }
  }

  /**
   * Shows update form.
   * @param  string $name
   * @return string|Response
   * @throws NotFoundHttpException
   * @throws Yii\base\InvalidConfigException
   */
  public function actionUpdate($type, $name, $description=null, $rulename=null)
  {
      /** @var \dektrium\rbac\models\Role|\dektrium\rbac\models\Permission $model */
      $type = $this->guessType($type);
      if ($type == Item::TYPE_ROLE) {
        $this->modelClass = Role::className();
      } else {
        $this->modelClass = Permission::className();
      }
      $item  = Yii::$app->authManager->getItem($name);
      $model = Yii::createObject([
          'class'    => $this->modelClass,
          'scenario' => 'update',
          'item'     => $item,
      ]);
      $model->setAttributes([
        'name' =>$name,
        'description' => $description,
        'rulename' => $rulename,
      ]);
      if ($model->save()) {
        $this->stdout(Yii::t('rbac', "Item has been updated\n"));
      } else {
        $this->stderr(Yii::t('rbac', "Cannot update Item\n{errors}\n", ['errors' => VarDumper::dumpAsString($model->firstErrors)]));
      }
  }

  /**
   * Deletes item.
   * @param  string $name
   * @return Response
   * @throws NotFoundHttpException
   */
  public function actionDelete($name)
  {
      if (($item  = Yii::$app->authManager->getItem($name)) && (Yii::$app->authManager->remove($item))){
        $this->stdout(Yii::t('rbac', "Item has been removed\n"));
      } else {
        $this->stderr(Yii::t('rbac', "Cannot remove Item: {name}\n", ['name' => $name]));
      }
  }
}
