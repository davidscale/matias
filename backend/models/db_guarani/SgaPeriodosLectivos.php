<?php

namespace backend\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_periodos_lectivos".
 *
 * @property int $periodo_lectivo
 * @property int $periodo
 * @property string $fecha_inicio_dictado
 * @property string $fecha_fin_dictado
 * @property string $fecha_tope_movimientos
 * @property string $fecha_inactivacion
 * @property string|null $fecha_publicacion_comisiones
 * @property int $entidad
 * @property string|null $fecha_publicacion_comisiones_docente
 *
 * @property SgaG3entidades $entidad0
 * @property GdeHabilitaciones[] $gdeHabilitaciones
 * @property GdeItems[] $gdeItems
 * @property SgaPeriodos $periodo0
 * @property SgaComisiones[] $sgaComisiones
 * @property SgaInscCursadaActividad[] $sgaInscCursadaActividads
 * @property SgaLicencias[] $sgaLicencias
 * @property SgaPeriodosLectivosTramos[] $sgaPeriodosLectivosTramos
 */
class SgaPeriodosLectivos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_periodos_lectivos';
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
            [['periodo', 'fecha_inicio_dictado', 'fecha_fin_dictado', 'fecha_tope_movimientos', 'fecha_inactivacion', 'entidad'], 'required'],
            [['periodo', 'entidad'], 'default', 'value' => null],
            [['periodo', 'entidad'], 'integer'],
            [['fecha_inicio_dictado', 'fecha_fin_dictado', 'fecha_tope_movimientos', 'fecha_inactivacion', 'fecha_publicacion_comisiones', 'fecha_publicacion_comisiones_docente'], 'safe'],
            [['entidad'], 'exist', 'skipOnError' => true, 'targetClass' => SgaG3entidades::className(), 'targetAttribute' => ['entidad' => 'entidad']],
            [['periodo'], 'exist', 'skipOnError' => true, 'targetClass' => SgaPeriodos::className(), 'targetAttribute' => ['periodo' => 'periodo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'periodo_lectivo' => Yii::t('app', 'Periodo Lectivo'),
            'periodo' => Yii::t('app', 'Periodo'),
            'fecha_inicio_dictado' => Yii::t('app', 'Fecha Inicio Dictado'),
            'fecha_fin_dictado' => Yii::t('app', 'Fecha Fin Dictado'),
            'fecha_tope_movimientos' => Yii::t('app', 'Fecha Tope Movimientos'),
            'fecha_inactivacion' => Yii::t('app', 'Fecha Inactivacion'),
            'fecha_publicacion_comisiones' => Yii::t('app', 'Fecha Publicacion Comisiones'),
            'entidad' => Yii::t('app', 'Entidad'),
            'fecha_publicacion_comisiones_docente' => Yii::t('app', 'Fecha Publicacion Comisiones Docente'),
        ];
    }

    /**
     * Gets query for [[Entidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntidad0()
    {
        return $this->hasOne(SgaG3entidades::className(), ['entidad' => 'entidad']);
    }

    /**
     * Gets query for [[GdeHabilitaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeHabilitaciones()
    {
        return $this->hasMany(GdeHabilitaciones::className(), ['periodo_lectivo' => 'periodo_lectivo']);
    }

    /**
     * Gets query for [[GdeItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeItems()
    {
        return $this->hasMany(GdeItems::className(), ['periodo_lectivo' => 'periodo_lectivo']);
    }

    /**
     * Gets query for [[Periodo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodo0()
    {
        return $this->hasOne(SgaPeriodos::className(), ['periodo' => 'periodo']);
    }

    /**
     * Gets query for [[SgaComisiones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisiones()
    {
        return $this->hasMany(SgaComisiones::className(), ['periodo_lectivo' => 'periodo_lectivo']);
    }

    /**
     * Gets query for [[SgaInscCursadaActividads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaInscCursadaActividads()
    {
        return $this->hasMany(SgaInscCursadaActividad::className(), ['periodo_lectivo' => 'periodo_lectivo']);
    }

    /**
     * Gets query for [[SgaLicencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaLicencias()
    {
        return $this->hasMany(SgaLicencias::className(), ['periodo_lectivo_desde' => 'periodo_lectivo']);
    }

    /**
     * Gets query for [[SgaPeriodosLectivosTramos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPeriodosLectivosTramos()
    {
        return $this->hasMany(SgaPeriodosLectivosTramos::className(), ['periodo_lectivo' => 'periodo_lectivo']);
    }
}
