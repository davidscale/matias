<?php

namespace backend\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_ubicaciones".
 *
 * @property int $ubicacion
 * @property string $nombre
 * @property int $ubicacion_tipo
 * @property int $localidad
 * @property string|null $calle
 * @property string|null $numero
 * @property string|null $codigo_postal
 * @property string|null $telefono
 * @property string|null $fax
 * @property string|null $email
 * @property int|null $institucion_araucano
 * @property float|null $latitud
 * @property float|null $longitud
 *
 * @property GdeUbicaciones[] $gdeUbicaciones
 * @property GdeHabilitaciones[] $habilitacions
 * @property IntArauInstituciones $institucionAraucano
 * @property MugLocalidades $localidad0
 * @property MenDominio[] $menDominios
 * @property SgaPeriodosInscripcion[] $periodoInscripcions
 * @property PreTurnosConfigUbicacion[] $preTurnosConfigUbicacions
 * @property SgaPropuestas[] $propuestas
 * @property SgaAlumnos[] $sgaAlumnos
 * @property SgaAlumnosHistUbicacion[] $sgaAlumnosHistUbicacions
 * @property SgaCertificadosResoluciones[] $sgaCertificadosResoluciones
 * @property SgaComisiones[] $sgaComisiones
 * @property SgaDiasNoLaborables[] $sgaDiasNoLaborables
 * @property SgaEdificaciones[] $sgaEdificaciones
 * @property SgaLibrosActasUbicacion[] $sgaLibrosActasUbicacions
 * @property SgaMesasExamen[] $sgaMesasExamens
 * @property SgaPerInscUbicacion[] $sgaPerInscUbicacions
 * @property SgaPreinscripcionPropuesta[] $sgaPreinscripcionPropuestas
 * @property SgaPropuestasAspira[] $sgaPropuestasAspiras
 * @property SgaPropuestasOferta[] $sgaPropuestasOfertas
 * @property SgaPropuestasRegularidad[] $sgaPropuestasRegularidads
 * @property SgaUgPropuestas[] $sgaUgPropuestas
 * @property PreTurnosConfig[] $turnoConfigs
 * @property SgaUbicacionesTipos $ubicacionTipo
 */
class SgaUbicaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_ubicaciones';
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
            [['nombre', 'ubicacion_tipo', 'localidad'], 'required'],
            [['ubicacion_tipo', 'localidad', 'institucion_araucano'], 'default', 'value' => null],
            [['ubicacion_tipo', 'localidad', 'institucion_araucano'], 'integer'],
            [['latitud', 'longitud'], 'number'],
            [['nombre', 'calle', 'email'], 'string', 'max' => 100],
            [['numero'], 'string', 'max' => 20],
            [['codigo_postal'], 'string', 'max' => 15],
            [['telefono', 'fax'], 'string', 'max' => 50],
            [['institucion_araucano'], 'exist', 'skipOnError' => true, 'targetClass' => IntArauInstituciones::className(), 'targetAttribute' => ['institucion_araucano' => 'institucion_araucano']],
            [['localidad'], 'exist', 'skipOnError' => true, 'targetClass' => MugLocalidades::className(), 'targetAttribute' => ['localidad' => 'localidad']],
            [['ubicacion_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => SgaUbicacionesTipos::className(), 'targetAttribute' => ['ubicacion_tipo' => 'ubicacion_tipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ubicacion' => Yii::t('app', 'Ubicacion'),
            'nombre' => Yii::t('app', 'Nombre'),
            'ubicacion_tipo' => Yii::t('app', 'Ubicacion Tipo'),
            'localidad' => Yii::t('app', 'Localidad'),
            'calle' => Yii::t('app', 'Calle'),
            'numero' => Yii::t('app', 'Numero'),
            'codigo_postal' => Yii::t('app', 'Codigo Postal'),
            'telefono' => Yii::t('app', 'Telefono'),
            'fax' => Yii::t('app', 'Fax'),
            'email' => Yii::t('app', 'Email'),
            'institucion_araucano' => Yii::t('app', 'Institucion Araucano'),
            'latitud' => Yii::t('app', 'Latitud'),
            'longitud' => Yii::t('app', 'Longitud'),
        ];
    }

    /**
     * Gets query for [[GdeUbicaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeUbicaciones()
    {
        return $this->hasMany(GdeUbicaciones::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[Habilitacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHabilitacions()
    {
        return $this->hasMany(GdeHabilitaciones::className(), ['habilitacion' => 'habilitacion'])->via('gdeUbicaciones');
    }

    /**
     * Gets query for [[InstitucionAraucano]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstitucionAraucano()
    {
        return $this->hasOne(IntArauInstituciones::className(), ['institucion_araucano' => 'institucion_araucano']);
    }

    /**
     * Gets query for [[Localidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad0()
    {
        return $this->hasOne(MugLocalidades::className(), ['localidad' => 'localidad']);
    }

    /**
     * Gets query for [[MenDominios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenDominios()
    {
        return $this->hasMany(MenDominio::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[PeriodoInscripcions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoInscripcions()
    {
        return $this->hasMany(SgaPeriodosInscripcion::className(), ['periodo_inscripcion' => 'periodo_inscripcion'])->via('sgaPerInscUbicacions');
    }

    /**
     * Gets query for [[PreTurnosConfigUbicacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreTurnosConfigUbicacions()
    {
        return $this->hasMany(PreTurnosConfigUbicacion::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[Propuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPropuestas()
    {
        return $this->hasMany(SgaPropuestas::className(), ['propuesta' => 'propuesta'])->via('sgaPropuestasOfertas');
    }

    /**
     * Gets query for [[SgaAlumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaAlumnos()
    {
        return $this->hasMany(SgaAlumnos::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaAlumnosHistUbicacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaAlumnosHistUbicacions()
    {
        return $this->hasMany(SgaAlumnosHistUbicacion::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaCertificadosResoluciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaCertificadosResoluciones()
    {
        return $this->hasMany(SgaCertificadosResoluciones::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaComisiones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisiones()
    {
        return $this->hasMany(SgaComisiones::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaDiasNoLaborables]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaDiasNoLaborables()
    {
        return $this->hasMany(SgaDiasNoLaborables::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaEdificaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaEdificaciones()
    {
        return $this->hasMany(SgaEdificaciones::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaLibrosActasUbicacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaLibrosActasUbicacions()
    {
        return $this->hasMany(SgaLibrosActasUbicacion::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaMesasExamens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaMesasExamens()
    {
        return $this->hasMany(SgaMesasExamen::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaPerInscUbicacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPerInscUbicacions()
    {
        return $this->hasMany(SgaPerInscUbicacion::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaPreinscripcionPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPreinscripcionPropuestas()
    {
        return $this->hasMany(SgaPreinscripcionPropuesta::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaPropuestasAspiras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasAspiras()
    {
        return $this->hasMany(SgaPropuestasAspira::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaPropuestasOfertas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasOfertas()
    {
        return $this->hasMany(SgaPropuestasOferta::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaPropuestasRegularidads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPropuestasRegularidads()
    {
        return $this->hasMany(SgaPropuestasRegularidad::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[SgaUgPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaUgPropuestas()
    {
        return $this->hasMany(SgaUgPropuestas::className(), ['ubicacion' => 'ubicacion']);
    }

    /**
     * Gets query for [[TurnoConfigs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurnoConfigs()
    {
        return $this->hasMany(PreTurnosConfig::className(), ['turno_config' => 'turno_config'])->via('preTurnosConfigUbicacions');
    }

    /**
     * Gets query for [[UbicacionTipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUbicacionTipo()
    {
        return $this->hasOne(SgaUbicacionesTipos::className(), ['ubicacion_tipo' => 'ubicacion_tipo']);
    }
}
