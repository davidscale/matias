<?php

namespace backend\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_comisiones".
 *
 * @property int $comision
 * @property string $nombre
 * @property int $periodo_lectivo
 * @property int $elemento
 * @property int|null $turno
 * @property int|null $entidad
 * @property int|null $catedra
 * @property string|null $letra_desde
 * @property string|null $letra_hasta
 * @property int|null $cupo_minimo
 * @property int|null $cupo
 * @property string $inscripcion_habilitada
 * @property int $ubicacion
 * @property int|null $division
 * @property string $asistencia_por_horas
 * @property string $asistencia_cantidad_inasist
 * @property string|null $observaciones
 * @property string $acepta_planes_personalizados
 * @property string $cobrable
 * @property string $estado
 * @property string $inscripcion_cerrada
 * @property string|null $inscripcion_cerrada_codigo
 *
 * @property SgaAlumnos[] $alumnos
 * @property SgaAlumnos[] $alumnos0
 * @property SgaAlumnos[] $alumnos1
 * @property SgaAsignaciones[] $asignacions
 * @property SgaCatedras $catedra0
 * @property IntPvCursos[] $cursos
 * @property SgaDivisiones $division0
 * @property SgaDocentes[] $docentes
 * @property SgaElementos $elemento0
 * @property SgaG3entidades $entidad0
 * @property GdeConcepto[] $gdeConceptos
 * @property GdeElemento[] $gdeElementos
 * @property GdeFormularios[] $gdeFormularios
 * @property GdeItems[] $gdeItems
 * @property SgaInstancias[] $instancias
 * @property IntPvCursosComisiones[] $intPvCursosComisiones
 * @property MenDominio[] $menDominios
 * @property SgaModalidadCursada[] $modalidads
 * @property SgaPeriodosLectivos $periodoLectivo
 * @property SgaActas[] $sgaActas
 * @property SgaAlumnosExcepAsistencia[] $sgaAlumnosExcepAsistencias
 * @property SgaClasesAsistenciaAcum[] $sgaClasesAsistenciaAcums
 * @property SgaComisionesBh[] $sgaComisionesBhs
 * @property SgaComisionesCupo $sgaComisionesCupo
 * @property SgaComisionesExcepPerinsc[] $sgaComisionesExcepPerinscs
 * @property SgaComisionesExcepPerlect $sgaComisionesExcepPerlect
 * @property SgaComisionesInstancias[] $sgaComisionesInstancias
 * @property SgaComisionesModalidad[] $sgaComisionesModalidads
 * @property SgaComisionesPropuestas[] $sgaComisionesPropuestas
 * @property SgaDocentesComision[] $sgaDocentesComisions
 * @property SgaInscAutoExamen[] $sgaInscAutoExamens
 * @property SgaInscCursadaLog[] $sgaInscCursadaLogs
 * @property SgaInscCursada[] $sgaInscCursadas
 * @property SgaReconocimientoDoc[] $sgaReconocimientoDocs
 * @property SgaSubcomisiones[] $sgaSubcomisiones
 * @property SgaTurnosCursadas $turno0
 * @property SgaUbicaciones $ubicacion0
 */
class SgaComisiones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_comisiones';
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
            [['nombre', 'periodo_lectivo', 'elemento', 'ubicacion'], 'required'],
            [['periodo_lectivo', 'elemento', 'turno', 'entidad', 'catedra', 'cupo_minimo', 'cupo', 'ubicacion', 'division'], 'default', 'value' => null],
            [['periodo_lectivo', 'elemento', 'turno', 'entidad', 'catedra', 'cupo_minimo', 'cupo', 'ubicacion', 'division'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['letra_desde', 'letra_hasta', 'inscripcion_habilitada', 'asistencia_por_horas', 'asistencia_cantidad_inasist', 'acepta_planes_personalizados', 'cobrable', 'estado', 'inscripcion_cerrada'], 'string', 'max' => 1],
            [['observaciones'], 'string', 'max' => 300],
            [['inscripcion_cerrada_codigo'], 'string', 'max' => 10],
            [['elemento', 'periodo_lectivo', 'ubicacion', 'nombre'], 'unique', 'targetAttribute' => ['elemento', 'periodo_lectivo', 'ubicacion', 'nombre']],
            [['catedra'], 'exist', 'skipOnError' => true, 'targetClass' => SgaCatedras::className(), 'targetAttribute' => ['catedra' => 'catedra']],
            [['division'], 'exist', 'skipOnError' => true, 'targetClass' => SgaDivisiones::className(), 'targetAttribute' => ['division' => 'division']],
            [['elemento'], 'exist', 'skipOnError' => true, 'targetClass' => SgaElementos::className(), 'targetAttribute' => ['elemento' => 'elemento']],
            [['entidad'], 'exist', 'skipOnError' => true, 'targetClass' => SgaG3entidades::className(), 'targetAttribute' => ['entidad' => 'entidad']],
            [['periodo_lectivo'], 'exist', 'skipOnError' => true, 'targetClass' => SgaPeriodosLectivos::className(), 'targetAttribute' => ['periodo_lectivo' => 'periodo_lectivo']],
            [['turno'], 'exist', 'skipOnError' => true, 'targetClass' => SgaTurnosCursadas::className(), 'targetAttribute' => ['turno' => 'turno']],
            [['ubicacion'], 'exist', 'skipOnError' => true, 'targetClass' => SgaUbicaciones::className(), 'targetAttribute' => ['ubicacion' => 'ubicacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comision' => Yii::t('app', 'Comision'),
            'nombre' => Yii::t('app', 'Nombre'),
            'periodo_lectivo' => Yii::t('app', 'Periodo Lectivo'),
            'elemento' => Yii::t('app', 'Elemento'),
            'turno' => Yii::t('app', 'Turno'),
            'entidad' => Yii::t('app', 'Entidad'),
            'catedra' => Yii::t('app', 'Catedra'),
            'letra_desde' => Yii::t('app', 'Letra Desde'),
            'letra_hasta' => Yii::t('app', 'Letra Hasta'),
            'cupo_minimo' => Yii::t('app', 'Cupo Minimo'),
            'cupo' => Yii::t('app', 'Cupo'),
            'inscripcion_habilitada' => Yii::t('app', 'Inscripcion Habilitada'),
            'ubicacion' => Yii::t('app', 'Ubicacion'),
            'division' => Yii::t('app', 'Division'),
            'asistencia_por_horas' => Yii::t('app', 'Asistencia Por Horas'),
            'asistencia_cantidad_inasist' => Yii::t('app', 'Asistencia Cantidad Inasist'),
            'observaciones' => Yii::t('app', 'Observaciones'),
            'acepta_planes_personalizados' => Yii::t('app', 'Acepta Planes Personalizados'),
            'cobrable' => Yii::t('app', 'Cobrable'),
            'estado' => Yii::t('app', 'Estado'),
            'inscripcion_cerrada' => Yii::t('app', 'Inscripcion Cerrada'),
            'inscripcion_cerrada_codigo' => Yii::t('app', 'Inscripcion Cerrada Codigo'),
        ];
    }

    /**
     * Gets query for [[Alumnos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos()
    {
        return $this->hasMany(SgaAlumnos::className(), ['alumno' => 'alumno'])->via('sgaAlumnosExcepAsistencias');
    }

    /**
     * Gets query for [[Alumnos0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos0()
    {
        return $this->hasMany(SgaAlumnos::className(), ['alumno' => 'alumno'])->via('sgaClasesAsistenciaAcums');
    }

    /**
     * Gets query for [[Alumnos1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnos1()
    {
        return $this->hasMany(SgaAlumnos::className(), ['alumno' => 'alumno'])->via('sgaInscCursadas');
    }

    /**
     * Gets query for [[Asignacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacions()
    {
        return $this->hasMany(SgaAsignaciones::className(), ['asignacion' => 'asignacion'])->via('sgaComisionesBhs');
    }

    /**
     * Gets query for [[Catedra0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatedra0()
    {
        return $this->hasOne(SgaCatedras::className(), ['catedra' => 'catedra']);
    }

    /**
     * Gets query for [[Cursos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursos()
    {
        return $this->hasMany(IntPvCursos::className(), ['curso' => 'curso'])->via('intPvCursosComisiones');
    }

    /**
     * Gets query for [[Division0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDivision0()
    {
        return $this->hasOne(SgaDivisiones::className(), ['division' => 'division']);
    }

    /**
     * Gets query for [[Docentes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocentes()
    {
        return $this->hasMany(SgaDocentes::className(), ['docente' => 'docente'])->via('sgaDocentesComisions');
    }

    /**
     * Gets query for [[Elemento0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getElemento0()
    {
        return $this->hasOne(SgaElementos::className(), ['elemento' => 'elemento']);
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
     * Gets query for [[GdeConceptos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeConceptos()
    {
        return $this->hasMany(GdeConcepto::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[GdeElementos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeElementos()
    {
        return $this->hasMany(GdeElemento::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[GdeFormularios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeFormularios()
    {
        return $this->hasMany(GdeFormularios::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[GdeItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGdeItems()
    {
        return $this->hasMany(GdeItems::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[Instancias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstancias()
    {
        return $this->hasMany(SgaInstancias::className(), ['instancia' => 'instancia'])->via('sgaComisionesInstancias');
    }

    /**
     * Gets query for [[IntPvCursosComisiones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIntPvCursosComisiones()
    {
        return $this->hasMany(IntPvCursosComisiones::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[MenDominios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenDominios()
    {
        return $this->hasMany(MenDominio::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[Modalidads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModalidads()
    {
        return $this->hasMany(SgaModalidadCursada::className(), ['modalidad' => 'modalidad'])->via('sgaComisionesModalidads');
    }

    /**
     * Gets query for [[PeriodoLectivo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoLectivo()
    {
        return $this->hasOne(SgaPeriodosLectivos::className(), ['periodo_lectivo' => 'periodo_lectivo']);
    }

    /**
     * Gets query for [[SgaActas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaActas()
    {
        return $this->hasMany(SgaActas::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaAlumnosExcepAsistencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaAlumnosExcepAsistencias()
    {
        return $this->hasMany(SgaAlumnosExcepAsistencia::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaClasesAsistenciaAcums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaClasesAsistenciaAcums()
    {
        return $this->hasMany(SgaClasesAsistenciaAcum::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaComisionesBhs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesBhs()
    {
        return $this->hasMany(SgaComisionesBh::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaComisionesCupo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesCupo()
    {
        return $this->hasOne(SgaComisionesCupo::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaComisionesExcepPerinscs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesExcepPerinscs()
    {
        return $this->hasMany(SgaComisionesExcepPerinsc::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaComisionesExcepPerlect]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesExcepPerlect()
    {
        return $this->hasOne(SgaComisionesExcepPerlect::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaComisionesInstancias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesInstancias()
    {
        return $this->hasMany(SgaComisionesInstancias::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaComisionesModalidads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesModalidads()
    {
        return $this->hasMany(SgaComisionesModalidad::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaComisionesPropuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaComisionesPropuestas()
    {
        return $this->hasMany(SgaComisionesPropuestas::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaDocentesComisions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaDocentesComisions()
    {
        return $this->hasMany(SgaDocentesComision::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaInscAutoExamens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaInscAutoExamens()
    {
        return $this->hasMany(SgaInscAutoExamen::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaInscCursadaLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaInscCursadaLogs()
    {
        return $this->hasMany(SgaInscCursadaLog::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaInscCursadas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaInscCursadas()
    {
        return $this->hasMany(SgaInscCursada::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaReconocimientoDocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaReconocimientoDocs()
    {
        return $this->hasMany(SgaReconocimientoDoc::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[SgaSubcomisiones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaSubcomisiones()
    {
        return $this->hasMany(SgaSubcomisiones::className(), ['comision' => 'comision']);
    }

    /**
     * Gets query for [[Turno0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTurno0()
    {
        return $this->hasOne(SgaTurnosCursadas::className(), ['turno' => 'turno']);
    }

    /**
     * Gets query for [[Ubicacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUbicacion0()
    {
        return $this->hasOne(SgaUbicaciones::className(), ['ubicacion' => 'ubicacion']);
    }
}
