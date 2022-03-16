<?php

namespace app\models\db_guarani;

use Yii;

/**
 * This is the model class for table "sga_periodos_genericos".
 *
 * @property int $periodo_generico
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $periodo_generico_tipo
 * @property int $orden_en_su_tipo
 * @property string|null $periodo_lectivo_tipo
 * @property string $activo
 *
 * @property SgaPeriodosGenericosTipo $periodoGenericoTipo
 * @property SgaPeriodosLectivosTipo $periodoLectivoTipo
 * @property SgaElementosPlan[] $sgaElementosPlans
 * @property SgaPeriodo[] $sgaPeriodos
 */
class SgaPeriodoGenerico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sga_periodos_genericos';
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
            [['nombre', 'periodo_generico_tipo'], 'required'],
            [['periodo_generico_tipo', 'orden_en_su_tipo'], 'default', 'value' => null],
            [['periodo_generico_tipo', 'orden_en_su_tipo'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 255],
            [['periodo_lectivo_tipo'], 'string', 'max' => 20],
            [['activo'], 'string', 'max' => 1],
            [['periodo_generico_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => SgaPeriodosGenericosTipo::className(), 'targetAttribute' => ['periodo_generico_tipo' => 'periodo_generico_tipo']],
            [['periodo_lectivo_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => SgaPeriodosLectivosTipo::className(), 'targetAttribute' => ['periodo_lectivo_tipo' => 'periodo_lectivo_tipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'periodo_generico' => Yii::t('app', 'Periodo Generico'),
            'nombre' => Yii::t('app', 'Nombre'),
            'descripcion' => Yii::t('app', 'Descripcion'),
            'periodo_generico_tipo' => Yii::t('app', 'Periodo Generico Tipo'),
            'orden_en_su_tipo' => Yii::t('app', 'Orden En Su Tipo'),
            'periodo_lectivo_tipo' => Yii::t('app', 'Periodo Lectivo Tipo'),
            'activo' => Yii::t('app', 'Activo'),
        ];
    }

    /**
     * Gets query for [[PeriodoGenericoTipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoGenericoTipo()
    {
        return $this->hasOne(SgaPeriodosGenericosTipo::className(), ['periodo_generico_tipo' => 'periodo_generico_tipo']);
    }

    /**
     * Gets query for [[PeriodoLectivoTipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoLectivoTipo()
    {
        return $this->hasOne(SgaPeriodosLectivosTipo::className(), ['periodo_lectivo_tipo' => 'periodo_lectivo_tipo']);
    }

    /**
     * Gets query for [[SgaElementosPlans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaElementosPlans()
    {
        return $this->hasMany(SgaElementosPlan::className(), ['periodo_de_cursada' => 'periodo_generico']);
    }

    /**
     * Gets query for [[SgaPeriodos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSgaPeriodos()
    {
        return $this->hasMany(SgaPeriodo::className(), ['periodo_generico' => 'periodo_generico']);
    }
}
