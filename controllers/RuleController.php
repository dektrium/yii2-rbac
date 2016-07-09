<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace dektrium\rbac\controllers;

use dektrium\rbac\models\Rule;
use dektrium\rbac\models\RuleSearch;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Controller for managing rules.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RuleController extends Controller
{
    /**
     * Shows list of created rules.
     * 
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel  = $this->getSearchModel();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Shows page where new rule can be added.
     * 
     * @return array|string
     */
    public function actionCreate()
    {
        $model = $this->getModel(Rule::SCENARIO_CREATE);

        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(\Yii::$app->request->post()) && $model->create()) {
            \Yii::$app->session->setFlash('success', \Yii::t('rbac', 'Rule has been added'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Searches for rules.
     *
     * @param  string|null $q
     * @return array
     */
    public function actionSearch($q = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return ['results' => $this->getSearchModel()->getRuleNames($q)];
    }

    /**
     * @param  string $scenario
     * @return Rule
     * @throws \yii\base\InvalidConfigException
     */
    private function getModel($scenario)
    {
        return \Yii::createObject([
            'class'    => Rule::className(),
            'scenario' => $scenario,
        ]);
    }

    /**
     * @return RuleSearch
     * @throws \yii\base\InvalidConfigException
     */
    private function getSearchModel()
    {
        return \Yii::createObject(RuleSearch::className());
    }
}