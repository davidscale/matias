<?php

namespace app\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_actas".
 *
 * @property int $id_acta
 * @property string $nro_acta
 * @property string $origen
 * @property string $tipo_acta
 * @property int|null $evaluacion
 * @property int|null $comision
 * @property int|null $llamado_mesa
 * @property string $fecha_generacion
 * @property string|null $fecha_cierre
 * @property string|null $fecha_anulacion
 * @property string|null $nua
 * @property int|null $documento
 * @property int $version
 * @property int $version_impresa
 * @property int $nro_ultima_copia
 * @property int $renglones_folio
 * @property int|null $acta_referencia
 * @property string|null $observaciones
 * @property string $estado
 * @property string $cerrada_por_docente
 * @property string|null $id_documento_digital
 * @property string|null $estado_documento_digital
 * @property string|null $parametros_documento_digital
 *
 * @property SgaAlumnos[] $alumnos
 * @property SgaAlumnos[] $alumnos0
 * @property SgaComisiones $comision0
 * @property SgaDocumentos $documento0
 * @property SgaActasEstados $estado0
 * @property SgaEvaluaciones $evaluacion0
 * @property SgaInstancias[] $instancias
 * @property SgaLlamadosMesa $llamadoMesa
 * @property MenDominio[] $menDominios
 * @property SgaActasOrigen $origen0
 * @property SgaActasAlumnosNoProm[] $sgaActasAlumnosNoProms
 * @property SgaActasDetalle[] $sgaActasDetalles
 * @property SgaActasFolios[] $sgaActasFolios
 * @property SgaActasInstancias[] $sgaActasInstancias
 * @property SgaEquivActasProcesadas[] $sgaEquivActasProcesadas
 * @property SgaEvalDetalleCursadas[] $sgaEvalDetalleCursadas
 * @property SgaEvalDetalleCursadas[] $sgaEvalDetalleCursadas0
 * @property SgaEvalDetalleExamenes[] $sgaEvalDetalleExamenes
 * @property SgaRegularidadesVenc[] $sgaRegularidadesVencs
 */
class SgaActas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_actas';
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
            [['nro_acta', 'origen', 'renglones_folio'], 'required'],
            [['evaluacion', 'comision', 'llamado_mesa', 'documento', 'version', 'version_impresa', 'nro_ultima_copia', 'renglones_folio', 'acta_referencia'], 'default', 'value' => null],
            [['evaluacion', 'comision', 'llamado_mesa', 'documento', 'version', 'version_impresa', 'nro_ultima_copia', 'renglones_folio', 'acta_referencia'], 'integer'],
            [['fecha_generacion', 'fecha_cierre', 'fecha_anulacion'], 'safe'],
            [['observaciones', 'id_documento_digital', 'parametros_documento_digital'], 'string'],
            [['nro_acta', 'nua'], 'string', 'max' => 30],
            [['origen', 'tipo_acta', 'estado', 'cerrada_por_docente', 'estado_documento_digital'], 'string', 'max' => 1],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => SgaActasEstados::className(), 'targetAttribute' => ['estado' => 'estado']],
            [['origen'], 'exist', 'skipOnError' => true, 'targetClass' => SgaActasOrigen::className(), 'targetAttribute' => ['origen' => 'origen']],
            [['comision'], 'exist', 'skipOnError' => true, 'targetClass' => SgaComisiones::className(), 'targetAttribute' => ['comision' => 'comision']],
            [['documento'], 'exist', 'skipOnError' => true, 'targetClass' => SgaDocumentos::className(), 'targetAttribute' => ['documento' => 'documento']],
            [['evaluacion'], 'exist', 'skipOnError' => true, 'targetClass' => SgaEvaluaciones::className(), 'targetAttribute' => ['evaluacion' => 'evaluacion']],
            [['llamado_mesa'], 'exist', 'skipOnError' => true, 'targetClass' => SgaLlamadosMesa::className(), 'targetAttribute' => ['llamado_mesa' => 'llamado_mesa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_acta' => Yii::t('app', 'Id Acta'),
            'nro_acta' => Yii::t('app', 'Nro Acta'),
            'origen' => Yii::t('app', 'Origen'),
            'tipo_acta' => Yii::t('app', 'Tipo Acta'),
            'evaluacion' => Yii::t('app', 'Evaluacion'),
            'comision' => Yii::t('app', 'Comision'),
            'llamado_mesa' => Yii::t('app', 'Llamado Mesa'),
            'fecha_generacion' => Yii::t('app', 'Fecha Generacion'),
            'fecha_cierre' => Yii::t('app', 'Fecha Cierre'),
            'fecha_anulacion' => Yii::t('app', 'Fecha Anulacion'),
            'nua' => Yii::t('app', 'Nua'),
            'documento' => Yii::t('app', 'Documento'),
            'version' => Yii::t('app', 'Version'),
            'version_impresa' => Yii::t('app', 'Version Impresa'),
            'nro_ultima_copia' => Yii::t('app', 'Nro Ultima Copia'),
            'renglones_folio' => Yii::t('app', 'Renglones Folio'),
            'acta_referencia' => Yii::t('app', 'Acta Referencia'),
            'observaciones' => Yii::t('app', 'Observaciones'),
            'estado' => Yii::t('app', 'Estado'),
            'cerrada_por_docente' => Yii::t('app', 'Cerrada Por Docente'),
            'id_documento_digital' => Yii::t('app', 'Id Documento Digital'),
            'estado_documento_digital' => Yii::t('app', 'Estado Documento Digital'),
            'parametros_documento_digital' => Yii::t('app', 'Parametros Documento Digital'),
        ];
    }

    /**
     * Gets query for [[Alumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos()
    {
        return $this->hasMany(SgaAlumnos::className(), ['alumno' => 'alumno'])->viaTable('sga_actas_alumnos_no_prom', ['acta' => 'id_acta']);
    }

    /**
     * Gets query for [[Alumnos0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos0()
    {
        return $this->hasMany(SgaAlumnos::className(), ['alumno' => 'alumno'])->viaTable('sga_actas_detalle', ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[Comision0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComision0()
    {
        return $this->hasOne(SgaComisiones::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[Documento0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumento0()
    {
        return $this->hasOne(SgaDocumentos::className(), ['documento' => 'documento']);
    }

    /**
     * Gets query for [[Estado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(SgaActasEstados::className(), ['estado' => 'estado']);
    }

    /**
     * Gets query for [[Evaluacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluacion0()
    {
        return $this->hasOne(SgaEvaluaciones::className(), ['evaluacion' => 'evaluacion']);
    }

    /**
     * Gets query for [[Instancias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstancias()
    {
        return $this->hasMany(SgaInstancias::className(), ['instancia' => 'instancia'])->viaTable('sga_actas_instancias', ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[LlamadoMesa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLlamadoMesa()
    {
        return $this->hasOne(SgaLlamadosMesa::className(), ['llamado_mesa' => 'llamado_mesa']);
    }

    /**
     * Gets query for [[MenDominios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenDominios()
    {
        return $this->hasMany(MenDominio::className(), ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[Origen0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrigen0()
    {
        return $this->hasOne(SgaActasOrigen::className(), ['origen' => 'origen']);
    }

    /**
     * Gets query for [[SgaActasAlumnosNoProms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaActasAlumnosNoProms()
    {
        return $this->hasMany(SgaActasAlumnosNoProm::className(), ['acta' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaActasDetalles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaActasDetalles()
    {
        return $this->hasMany(SgaActasDetalle::className(), ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaActasFolios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaActasFolios()
    {
        return $this->hasMany(SgaActasFolios::className(), ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaActasInstancias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaActasInstancias()
    {
        return $this->hasMany(SgaActasInstancias::className(), ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaEquivActasProcesadas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEquivActasProcesadas()
    {
        return $this->hasMany(SgaEquivActasProcesadas::className(), ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaEvalDetalleCursadas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEvalDetalleCursadas()
    {
        return $this->hasMany(SgaEvalDetalleCursadas::className(), ['id_acta_cursada' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaEvalDetalleCursadas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEvalDetalleCursadas0()
    {
        return $this->hasMany(SgaEvalDetalleCursadas::className(), ['id_acta_promocion' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaEvalDetalleExamenes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEvalDetalleExamenes()
    {
        return $this->hasMany(SgaEvalDetalleExamenes::className(), ['id_acta' => 'id_acta']);
    }

    /**
     * Gets query for [[SgaRegularidadesVencs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaRegularidadesVencs()
    {
        return $this->hasMany(SgaRegularidadesVenc::className(), ['id_acta_revalida' => 'id_acta']);
    }
}
