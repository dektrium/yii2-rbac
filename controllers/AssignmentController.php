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

use dektrium\rbac\models\Assignment;
use Yii;
use yii\web\Controller;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class AssignmentController extends Controller
{
    /**
     * Show form with auth items for user.
     * 
     * @param int $id
     */
    public function actionAssign($id)
    {
        $model = Yii::createObject([
            'class'   => Assignment::className(),
            'user_id' => $id,
        ]);
        
        if ($model->load(\Yii::$app->request->post()) && $model->updateAssignments()) {
        }

        return \dektrium\rbac\widgets\Assignments::widget([
            'model' => $model,
        ]);
        /*$model = Yii::createObject([
            'class'   => Assignment::className(),
            'user_id' => $id,
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $model->updateAssignments()) {
            
        }
        
        return $this->render('assign', [
            'model' => $model,
        ]);*/
    }
}