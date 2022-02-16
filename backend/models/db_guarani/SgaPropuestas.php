<?php

namespace backend\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_propuestas".
 *
 * @property int $propuesta
 * @property string $nombre
 * @property string $nombre_abreviado
 * @property string $codigo
 * @property int $propuesta_tipo
 * @property string $publica
 * @property int|null $documento_alta
 * @property string|null $fecha_alta
 * @property int|null $campo_disciplinar
 * @property int|null $escala_cumplimiento
 * @property int|null $documento_baja
 * @property string|null $fecha_baja
 * @property int|null $a_termino
 * @property int|null $entidad
 * @property string $estado
 *
 * @property SgaCampoDisciplinar $campoDisciplinar
 * @property SgaColaciones[] $colacions
 * @property SgaCompetenciasIngreso[] $competencias
 * @property SgaDocumentos $documentoAlta
 * @property SgaDocumentos $documentoBaja
 * @property SgaG3entidades $entidad0
 * @property SgaEscalasCumplimiento $escalaCumplimiento
 * @property SgaPropuestasEstados $estado0
 * @property GdeConcepto[] $gdeConceptos
 * @property GdeElemento[] $gdeElementos
 * @property GdeEncuestaPendientePropuestas[] $gdeEncuestaPendientePropuestas
 * @property GdeItems[] $gdeItems
 * @property GdePropuestas[] $gdePropuestas
 * @property SgaPropuestasGrupos[] $grupoPropuestas
 * @property GdeHabilitaciones[] $habilitacions
 * @property MenDominio[] $menDominios
 * @property MdpPersonas[] $personas
 * @property SgaPropuestasTipos $propuestaTipo
 * @property SgaResponsablesAcademicas[] $responsableAcademicas
 * @property SgaAlumnos[] $sgaAlumnos
 * @property SgaColacionesPropuestas[] $sgaColacionesPropuestas
 * @property SgaComisionesPropuestas[] $sgaComisionesPropuestas
 * @property SgaCompetenciasIngPropuesta[] $sgaCompetenciasIngPropuestas
 * @property SgaConvenios[] $sgaConvenios
 * @property SgaConveniosPropuestas[] $sgaConveniosPropuestas
 * @property SgaEquivInternas[] $sgaEquivInternas
 * @property SgaEquivMatrices[] $sgaEquivMatrices
 * @property SgaEquivTramite[] $sgaEquivTramites
 * @property SgaLibrosActasPropuesta[] $sgaLibrosActasPropuestas
 * @property SgaLicenciasPropuestas[] $sgaLicenciasPropuestas
 * @property SgaMesasExamenPropuestas[] $sgaMesasExamenPropuestas
 * @property SgaPeriodosInscripcionAplanado[] $sgaPeriodosInscripcionAplanados
 * @property SgaPeriodosInscripcionCoeficiente[] $sgaPeriodosInscripcionCoeficientes
 * @property SgaPlanes[] $sgaPlanes
 * @property SgaPreinscripcionPropuesta[] $sgaPreinscripcionPropuestas
 * @property SgaPropuestasAreasTem[] $sgaPropuestasAreasTems
 * @property SgaPropuestasAspira[] $sgaPropuestasAspiras
 * @property SgaPropuestasOferta[] $sgaPropuestasOfertas
 * @property SgaPropuestasRa[] $sgaPropuestasRas
 * @property SgaPropuestasRegularidad[] $sgaPropuestasRegularidads
 * @property SgaPropuestasRelacion[] $sgaPropuestasRelacions
 * @property SgaPropuestasXGrupo[] $sgaPropuestasXGrupos
 * @property SgaSanciones[] $sgaSanciones
 * @property SgaUgPropuestas[] $sgaUgPropuestas
 * @property SgaUbicaciones[] $ubicacions
 */
class SgaPropuestas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_propuestas';
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
            [['nombre', 'nombre_abreviado', 'codigo', 'propuesta_tipo', 'estado'], 'required'],
            [['propuesta_tipo', 'documento_alta', 'campo_disciplinar', 'escala_cumplimiento', 'documento_baja', 'a_termino', 'entidad'], 'default', 'value' => null],
            [['propuesta_tipo', 'documento_alta', 'campo_disciplinar', 'escala_cumplimiento', 'documento_baja', 'a_termino', 'entidad'], 'integer'],
            [['fecha_alta', 'fecha_baja'], 'safe'],
            [['nombre'], 'string', 'max' => 255],
            [['nombre_abreviado'], 'string', 'max' => 50],
            [['codigo'], 'string', 'max' => 10],
            [['publica', 'estado'], 'string', 'max' => 1],
            [['codigo'], 'unique'],
            [['campo_disciplinar'], 'exist', 'skipOnError' => true, 'targetClass' => SgaCampoDisciplinar::className(), 'targetAttribute' => ['campo_disciplinar' => 'campo_disciplinar']],
            [['documento_alta'], 'exist', 'skipOnError' => true, 'targetClass' => SgaDocumentos::className(), 'targetAttribute' => ['documento_alta' => 'documento']],
            [['documento_baja'], 'exist', 'skipOnError' => true, 'targetClass' => SgaDocumentos::className(), 'targetAttribute' => ['documento_baja' => 'documento']],
            [['escala_cumplimiento'], 'exist', 'skipOnError' => true, 'targetClass' => SgaEscalasCumplimiento::className(), 'targetAttribute' => ['escala_cumplimiento' => 'escala_cumplimiento']],
            [['entidad'], 'exist', 'skipOnError' => true, 'targetClass' => SgaG3entidades::className(), 'targetAttribute' => ['entidad' => 'entidad']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => SgaPropuestasEstados::className(), 'targetAttribute' => ['estado' => 'estado']],
            [['propuesta_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => SgaPropuestasTipos::className(), 'targetAttribute' => ['propuesta_tipo' => 'propuesta_tipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'propuesta' => Yii::t('app', 'Propuesta'),
            'nombre' => Yii::t('app', 'Nombre'),
            'nombre_abreviado' => Yii::t('app', 'Nombre Abreviado'),
            'codigo' => Yii::t('app', 'Codigo'),
            'propuesta_tipo' => Yii::t('app', 'Propuesta Tipo'),
            'publica' => Yii::t('app', 'Publica'),
            'documento_alta' => Yii::t('app', 'Documento Alta'),
            'fecha_alta' => Yii::t('app', 'Fecha Alta'),
            'campo_disciplinar' => Yii::t('app', 'Campo Disciplinar'),
            'escala_cumplimiento' => Yii::t('app', 'Escala Cumplimiento'),
            'documento_baja' => Yii::t('app', 'Documento Baja'),
            'fecha_baja' => Yii::t('app', 'Fecha Baja'),
            'a_termino' => Yii::t('app', 'A Termino'),
            'entidad' => Yii::t('app', 'Entidad'),
            'estado' => Yii::t('app', 'Estado'),
        ];
    }

    /**
     * Gets query for [[CampoDisciplinar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCampoDisciplinar()
    {
        return $this->hasOne(SgaCampoDisciplinar::className(), ['campo_disciplinar' => 'campo_disciplinar']);
    }

    /**
     * Gets query for [[Colacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getColacions()
    {
        return $this->hasMany(SgaColaciones::className(), ['colacion' => 'colacion'])->via('sgaColacionesPropuestas');
    }

    /**
     * Gets query for [[Competencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetencias()
    {
        return $this->hasMany(SgaCompetenciasIngreso::className(), ['competencia' => 'competencia'])->via('sgaCompetenciasIngPropuestas');
    }

    /**
     * Gets query for [[DocumentoAlta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentoAlta()
    {
        return $this->hasOne(SgaDocumentos::className(), ['documento' => 'documento_alta']);
    }

    /**
     * Gets query for [[DocumentoBaja]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentoBaja()
    {
        return $this->hasOne(SgaDocumentos::className(), ['documento' => 'documento_baja']);
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
     * Gets query for [[EscalaCumplimiento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEscalaCumplimiento()
    {
        return $this->hasOne(SgaEscalasCumplimiento::className(), ['escala_cumplimiento' => 'escala_cumplimiento']);
    }

    /**
     * Gets query for [[Estado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(SgaPropuestasEstados::className(), ['estado' => 'estado']);
    }

    /**
     * Gets query for [[GdeConceptos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeConceptos()
    {
        return $this->hasMany(GdeConcepto::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[GdeElementos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeElementos()
    {
        return $this->hasMany(GdeElemento::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[GdeEncuestaPendientePropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeEncuestaPendientePropuestas()
    {
        return $this->hasMany(GdeEncuestaPendientePropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[GdeItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeItems()
    {
        return $this->hasMany(GdeItems::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[GdePropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdePropuestas()
    {
        return $this->hasMany(GdePropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[GrupoPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoPropuestas()
    {
        return $this->hasMany(SgaPropuestasGrupos::className(), ['grupo_propuesta' => 'grupo_propuesta'])->via('sgaPropuestasXGrupos');
    }

    /**
     * Gets query for [[Habilitacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHabilitacions()
    {
        return $this->hasMany(GdeHabilitaciones::className(), ['habilitacion' => 'habilitacion'])->via('gdePropuestas');
    }

    /**
     * Gets query for [[MenDominios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenDominios()
    {
        return $this->hasMany(MenDominio::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[Personas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(MdpPersonas::className(), ['persona' => 'persona'])->via('sgaAlumnos');
    }

    /**
     * Gets query for [[PropuestaTipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPropuestaTipo()
    {
        return $this->hasOne(SgaPropuestasTipos::className(), ['propuesta_tipo' => 'propuesta_tipo']);
    }

    /**
     * Gets query for [[ResponsableAcademicas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsableAcademicas()
    {
        return $this->hasMany(SgaResponsablesAcademicas::className(), ['responsable_academica' => 'responsable_academica'])->via('sgaPropuestasRas');
    }

    /**
     * Gets query for [[SgaAlumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaAlumnos()
    {
        return $this->hasMany(SgaAlumnos::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaColacionesPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaColacionesPropuestas()
    {
        return $this->hasMany(SgaColacionesPropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaComisionesPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesPropuestas()
    {
        return $this->hasMany(SgaComisionesPropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaCompetenciasIngPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaCompetenciasIngPropuestas()
    {
        return $this->hasMany(SgaCompetenciasIngPropuesta::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaConvenios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaConvenios()
    {
        return $this->hasMany(SgaConvenios::className(), ['propuesta_base' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaConveniosPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaConveniosPropuestas()
    {
        return $this->hasMany(SgaConveniosPropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaEquivInternas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEquivInternas()
    {
        return $this->hasMany(SgaEquivInternas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaEquivMatrices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEquivMatrices()
    {
        return $this->hasMany(SgaEquivMatrices::className(), ['propuesta_origen' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaEquivTramites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEquivTramites()
    {
        return $this->hasMany(SgaEquivTramite::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaLibrosActasPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaLibrosActasPropuestas()
    {
        return $this->hasMany(SgaLibrosActasPropuesta::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaLicenciasPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaLicenciasPropuestas()
    {
        return $this->hasMany(SgaLicenciasPropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaMesasExamenPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaMesasExamenPropuestas()
    {
        return $this->hasMany(SgaMesasExamenPropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPeriodosInscripcionAplanados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPeriodosInscripcionAplanados()
    {
        return $this->hasMany(SgaPeriodosInscripcionAplanado::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPeriodosInscripcionCoeficientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPeriodosInscripcionCoeficientes()
    {
        return $this->hasMany(SgaPeriodosInscripcionCoeficiente::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPlanes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPlanes()
    {
        return $this->hasMany(SgaPlanes::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPreinscripcionPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPreinscripcionPropuestas()
    {
        return $this->hasMany(SgaPreinscripcionPropuesta::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPropuestasAreasTems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasAreasTems()
    {
        return $this->hasMany(SgaPropuestasAreasTem::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPropuestasAspiras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasAspiras()
    {
        return $this->hasMany(SgaPropuestasAspira::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPropuestasOfertas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasOfertas()
    {
        return $this->hasMany(SgaPropuestasOferta::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPropuestasRas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasRas()
    {
        return $this->hasMany(SgaPropuestasRa::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPropuestasRegularidads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasRegularidads()
    {
        return $this->hasMany(SgaPropuestasRegularidad::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPropuestasRelacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasRelacions()
    {
        return $this->hasMany(SgaPropuestasRelacion::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaPropuestasXGrupos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasXGrupos()
    {
        return $this->hasMany(SgaPropuestasXGrupo::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaSanciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaSanciones()
    {
        return $this->hasMany(SgaSanciones::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[SgaUgPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaUgPropuestas()
    {
        return $this->hasMany(SgaUgPropuestas::className(), ['propuesta' => 'propuesta']);
    }

    /**
     * Gets query for [[Ubicacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUbicacions()
    {
        return $this->hasMany(SgaUbicaciones::className(), ['ubicacion' => 'ubicacion'])->via('sgaPropuestasOfertas');
    }
}
