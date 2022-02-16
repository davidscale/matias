<?php

namespace backend\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_anios_academicos".
 *
 * @property float $anio_academico
 *
 * @property SgaAlumnos[] $alumnos
 * @property GdeAniosAcademicos[] $gdeAniosAcademicos
 * @property GdeHabilitaciones[] $gdeHabilitaciones
 * @property GdeItems[] $gdeItems
 * @property GdeHabilitaciones[] $habilitacions
 * @property MenDominio[] $menDominios
 * @property SgaAniosAcademicosFechas[] $sgaAniosAcademicosFechas
 * @property SgaConvenios[] $sgaConvenios
 * @property SgaLicencias[] $sgaLicencias
 * @property SgaMesasExamen[] $sgaMesasExamens
 * @property SgaPerdidaRegularidad[] $sgaPerdidaRegularidads
 * @property SgaPeriodos[] $sgaPeriodos
 * @property SgaPeriodosInscripcionPropuesta[] $sgaPeriodosInscripcionPropuestas
 * @property SgaPropuestasAspira[] $sgaPropuestasAspiras
 * @property SgaPropuestasRelacion[] $sgaPropuestasRelacions
 * @property SgaReadmisiones[] $sgaReadmisiones
 * @property SgaReadmisionesSolicitud[] $sgaReadmisionesSolicituds
 * @property SgaReinscripciones[] $sgaReinscripciones
 */
class SgaAniosAcademicos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_anios_academicos';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_guarani');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['anio_academico'], 'required'],
            [['anio_academico'], 'number'],
            [['anio_academico'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'anio_academico' => Yii::t('app', 'Anio Academico'),
        ];
    }

    /**
     * Gets query for [[Alumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos()
    {
        return $this->hasMany(SgaAlumnos::className(), ['alumno' => 'alumno'])->via('sgaReinscripciones');
    }

    /**
     * Gets query for [[GdeAniosAcademicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeAniosAcademicos()
    {
        return $this->hasMany(GdeAniosAcademicos::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[GdeHabilitaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeHabilitaciones()
    {
        return $this->hasMany(GdeHabilitaciones::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[GdeItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeItems()
    {
        return $this->hasMany(GdeItems::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[Habilitacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHabilitacions()
    {
        return $this->hasMany(GdeHabilitaciones::className(), ['habilitacion' => 'habilitacion'])->via('gdeAniosAcademicos');
    }

    /**
     * Gets query for [[MenDominios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenDominios()
    {
        return $this->hasMany(MenDominio::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaAniosAcademicosFechas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaAniosAcademicosFechas()
    {
        return $this->hasMany(SgaAniosAcademicosFechas::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaConvenios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaConvenios()
    {
        return $this->hasMany(SgaConvenios::className(), ['anio_academico_vigencia' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaLicencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaLicencias()
    {
        return $this->hasMany(SgaLicencias::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaMesasExamens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaMesasExamens()
    {
        return $this->hasMany(SgaMesasExamen::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaPerdidaRegularidads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPerdidaRegularidads()
    {
        return $this->hasMany(SgaPerdidaRegularidad::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaPeriodos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPeriodos()
    {
        return $this->hasMany(SgaPeriodos::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaPeriodosInscripcionPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPeriodosInscripcionPropuestas()
    {
        return $this->hasMany(SgaPeriodosInscripcionPropuesta::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaPropuestasAspiras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasAspiras()
    {
        return $this->hasMany(SgaPropuestasAspira::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaPropuestasRelacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasRelacions()
    {
        return $this->hasMany(SgaPropuestasRelacion::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaReadmisiones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaReadmisiones()
    {
        return $this->hasMany(SgaReadmisiones::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaReadmisionesSolicituds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaReadmisionesSolicituds()
    {
        return $this->hasMany(SgaReadmisionesSolicitud::className(), ['anio_academico' => 'anio_academico']);
    }

    /**
     * Gets query for [[SgaReinscripciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaReinscripciones()
    {
        return $this->hasMany(SgaReinscripciones::className(), ['anio_academico' => 'anio_academico']);
    }
}
