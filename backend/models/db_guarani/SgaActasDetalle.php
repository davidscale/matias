<?php

namespace app\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_actas_detalle".
 *
 * @property int $id_acta
 * @property int $alumno
 * @property int $instancia
 * @property int $plan_version
 * @property string|null $fecha
 * @property string|null $fecha_vigencia
 * @property int $folio
 * @property int $renglon
 * @property float|null $pct_asistencia
 * @property int|null $escala_nota
 * @property string|null $nota
 * @property string|null $resultado
 * @property int|null $cond_regularidad
 * @property string $rectificado
 * @property string|null $observaciones
 * @property string $estado
 *
 * @property SgaActas $acta
 * @property SgaAlumnos $alumno0
 * @property SgaCondRegularidad $condRegularidad
 * @property SgaEscalasNotasDet $escalaNota
 * @property SgaActasDetalleEstado $estado0
 * @property SgaInstancias $instancia0
 * @property SgaPlanesVersiones $planVersion
 * @property SgaEscalasNotasResultado $resultado0
 * @property SgaAusenciasExamen $sgaAusenciasExamen
 * @property SgaMovimientosHa[] $sgaMovimientosHas
 * @property SgaRegularidadesVenc[] $sgaRegularidadesVencs
 */
class SgaActasDetalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_actas_detalle';
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
            [['id_acta', 'alumno', 'instancia', 'plan_version', 'renglon'], 'required'],
            [['id_acta', 'alumno', 'instancia', 'plan_version', 'folio', 'renglon', 'escala_nota', 'cond_regularidad'], 'default', 'value' => null],
            [['id_acta', 'alumno', 'instancia', 'plan_version', 'folio', 'renglon', 'escala_nota', 'cond_regularidad'], 'integer'],
            [['fecha', 'fecha_vigencia'], 'safe'],
            [['pct_asistencia'], 'number'],
            [['nota'], 'string', 'max' => 10],
            [['resultado', 'rectificado', 'estado'], 'string', 'max' => 1],
            [['observaciones'], 'string', 'max' => 100],
            [['id_acta', 'alumno'], 'unique', 'targetAttribute' => ['id_acta', 'alumno']],
            [['id_acta'], 'exist', 'skipOnError' => true, 'targetClass' => SgaActas::className(), 'targetAttribute' => ['id_acta' => 'id_acta']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => SgaActasDetalleEstado::className(), 'targetAttribute' => ['estado' => 'estado']],
            [['alumno'], 'exist', 'skipOnError' => true, 'targetClass' => SgaAlumnos::className(), 'targetAttribute' => ['alumno' => 'alumno']],
            [['cond_regularidad'], 'exist', 'skipOnError' => true, 'targetClass' => SgaCondRegularidad::className(), 'targetAttribute' => ['cond_regularidad' => 'cond_regularidad']],
            [['escala_nota', 'nota'], 'exist', 'skipOnError' => true, 'targetClass' => SgaEscalasNotasDet::className(), 'targetAttribute' => ['escala_nota' => 'escala_nota', 'nota' => 'nota']],
            [['resultado'], 'exist', 'skipOnError' => true, 'targetClass' => SgaEscalasNotasResultado::className(), 'targetAttribute' => ['resultado' => 'resultado']],
            [['instancia'], 'exist', 'skipOnError' => true, 'targetClass' => SgaInstancias::className(), 'targetAttribute' => ['instancia' => 'instancia']],
            [['plan_version'], 'exist', 'skipOnError' => true, 'targetClass' => SgaPlanesVersiones::className(), 'targetAttribute' => ['plan_version' => 'plan_version']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_acta' => Yii::t('app', 'Id Acta'),
            'alumno' => Yii::t('app', 'Alumno'),
            'instancia' => Yii::t('app', 'Instancia'),
            'plan_version' => Yii::t('app', 'Plan Version'),
            'fecha' => Yii::t('app', 'Fecha'),
            'fecha_vigencia' => Yii::t('app', 'Fecha Vigencia'),
            'folio' => Yii::t('app', 'Folio'),
            'renglon' => Yii::t('app', 'Renglon'),
            'pct_asistencia' => Yii::t('app', 'Pct Asistencia'),
            'escala_nota' => Yii::t('app', 'Escala Nota'),
            'nota' => Yii::t('app', 'Nota'),
            'resultado' => Yii::t('app', 'Resultado'),
            'cond_regularidad' => Yii::t('app', 'Cond Regularidad'),
            'rectificado' => Yii::t('app', 'Rectificado'),
            'observaciones' => Yii::t('app', 'Observaciones'),
            'estado' => Yii::t('app', 'Estado'),
        ];
    }

    /**
     * Gets query for [[Acta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActa()
    {
        return $this->hasOne(SgaActas::className(), ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[Alumno0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumno0()
    {
        return $this->hasOne(SgaAlumnos::className(), ['alumno' => 'alumno']);
    }

    /**
     * Gets query for [[CondRegularidad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCondRegularidad()
    {
        return $this->hasOne(SgaCondRegularidad::className(), ['cond_regularidad' => 'cond_regularidad']);
    }

    /**
     * Gets query for [[EscalaNota]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEscalaNota()
    {
        return $this->hasOne(SgaEscalasNotasDet::className(), ['escala_nota' => 'escala_nota', 'nota' => 'nota']);
    }

    /**
     * Gets query for [[Estado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(SgaActasDetalleEstado::className(), ['estado' => 'estado']);
    }

    /**
     * Gets query for [[Instancia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstancia0()
    {
        return $this->hasOne(SgaInstancias::className(), ['instancia' => 'instancia']);
    }

    /**
     * Gets query for [[PlanVersion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanVersion()
    {
        return $this->hasOne(SgaPlanesVersiones::className(), ['plan_version' => 'plan_version']);
    }

    /**
     * Gets query for [[Resultado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResultado0()
    {
        return $this->hasOne(SgaEscalasNotasResultado::className(), ['resultado' => 'resultado']);
    }

    /**
     * Gets query for [[SgaAusenciasExamen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaAusenciasExamen()
    {
        return $this->hasOne(SgaAusenciasExamen::className(), ['id_acta' => 'id_acta', 'alumno' => 'alumno']);
    }

    /**
     * Gets query for [[SgaMovimientosHas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaMovimientosHas()
    {
        return $this->hasMany(SgaMovimientosHa::className(), ['id_acta' => 'id_acta', 'alumno' => 'alumno']);
    }

    /**
     * Gets query for [[SgaRegularidadesVencs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaRegularidadesVencs()
    {
        return $this->hasMany(SgaRegularidadesVenc::className(), ['id_acta' => 'id_acta', 'alumno' => 'alumno']);
    }
}
