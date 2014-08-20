<?php

/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $enabled
 * @property integer $type
 * @property integer $parent_id
 * @property integer $image_id
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property MenuLang[] $menuLangs
 */
class Menu extends CrudActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('type', 'required'),
			array('enabled, type, parent_id, image_id, sort', 'numerical', 'integerOnly'=>true),
			array('name, alias', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, alias, enabled, type, parent_id, image_id, sort', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'menuLangs' => array(self::HAS_MANY, 'MenuLang', 'entity_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'alias' => 'Alias',
			'enabled' => 'Enabled',
			'type' => 'Type',
			'parent_id' => 'Parent',
			'image_id' => 'Image',
			'sort' => 'Sort',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('type',$this->type);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('image_id',$this->image_id);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    private $recursiveCache;
    private $cachedRawData;

    private function getRawData(){
        if(isset($this->cachedRawData))
            return $this->cachedRawData;

        $this->cachedRawData =db()->createCommand()
            ->select('m.id, CONCAT("c", m.id) as url_id, m.alias, m.name, m.sort, m.parent_id, u.filename')
            ->from('{{menu}} m')
            ->leftJoin('{{upload}} u','u.id=m.image_id')
            ->order('m.parent_id')
            ->queryAll();
        return $this->cachedRawData;
    }


    public function getModelRawData($modelId){
        foreach($this->getRawData() as $model){
            if($model['id']==$modelId){
                return $model;
            }
        }
    }

    public function getDataForRecursiveRender(){
        if(isset($this->recursiveCache))
            return $this->recursiveCache;
        function model_sorter(&$models){
            usort($models, function($a, $b) {
                return $a['sort'] - $b['sort'];
            });
        }

        function model_searcher(&$models, $rawData){
            foreach($models as &$model){
                $model['children'] = array_filter($rawData, function($item) use ($model){
                    return $item['parent_id']==$model['id'];
                });
                if(count($model['children'])>0){
                    model_sorter($model['children']);
                    model_searcher($model['children'], $rawData);
                }
            }
        }


        $rawData = $this->getRawData();

        $models = array_filter($rawData, function($item){
            return $item['parent_id']=='0';
        });

        model_sorter($models);
        model_searcher($models, $rawData);
        $this->recursiveCache = $models;

        return $models;
    }

    public function behaviors()
    {
        return [
            'upload'=>[
                'class'=>'upload.components.UploadBehavior',
                'folder'=>'menu',
                'defaultUploadField'=>'image_id'
            ],
            'ml' => [
                'class' => 'MultilingualBehavior',
                'langTableName' => 'menu_lang',
                'langForeignKey' => 'entity_id',
                'localizedAttributes' => [
                    'name',
                ],
                'languages' => Lang::getLanguages(), // array of your translated languages. Example : ['fr' => 'Français', 'en' => 'English')
                'defaultLanguage' => Lang::getDefault(), //your main language. Example : 'fr'
                'dynamicLangClass' => true,
            ],
        ];
    }

}
