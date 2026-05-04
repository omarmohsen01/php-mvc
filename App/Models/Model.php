<?php

namespace App\Models;

use AllowDynamicProperties;
use PhpMvc\Support\Str;

#[AllowDynamicProperties]
abstract class Model
{
    protected static $instance;
    protected static $withRelations = [];
    public static $eagerLoading = false;
    public array $relations = [];

    public function __get($name)
    {
        if (array_key_exists($name, $this->relations)) {
            return $this->relations[$name];
        }

        if (method_exists($this, $name)) {
            $result = $this->$name();
            $this->relations[$name] = $result;
            return $result;
        }

        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }

    public static function with($relations)
    {
        self::$withRelations = is_array($relations) ? $relations : func_get_args();
        return new static;
    }

    protected static function loadRelations($models)
    {
        if (empty($models) || empty(self::$withRelations)) {
            return $models;
        }

        self::$eagerLoading = true;
        $dummy = $models[0];

        foreach (self::$withRelations as $relation) {
            if (method_exists($dummy, $relation)) {
                $meta = $dummy->$relation();
                
                $relatedClass = $meta['related'];
                $type = $meta['type'];
                $foreignKey = $meta['foreignKey'] ?? null;
                $localKey = $meta['localKey'] ?? null;

                $ids = [];
                if ($type !== 'belongsToMany') {
                    foreach ($models as $model) {
                        $ids[] = $model->{$localKey};
                    }
                    $ids = array_unique($ids);
                    if (empty($ids)) continue;

                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $tableName = Str::lower(Str::plural(class_basename($relatedClass)));
                    $query = "SELECT * FROM {$tableName} WHERE {$foreignKey} IN ({$placeholders})";
                    $relatedRows = app()->db->query($query, $ids);
                    
                    $relatedModels = [];
                    foreach ($relatedRows as $row) {
                        $m = new $relatedClass;
                        foreach ($row as $k => $v) {
                            $m->{$k} = $v;
                        }
                        $relatedModels[] = $m;
                    }

                    foreach ($models as $model) {
                        $matched = [];
                        foreach ($relatedModels as $rModel) {
                            if ($rModel->{$foreignKey} == $model->{$localKey}) {
                                $matched[] = $rModel;
                            }
                        }
                        if ($type === 'hasOne' || $type === 'belongsTo') {
                            $model->relations[$relation] = $matched[0] ?? null;
                        } else {
                            $model->relations[$relation] = $matched;
                        }
                    }
                } else {
                    $table = $meta['table'];
                    $foreignPivotKey = $meta['foreignPivotKey'];
                    $relatedPivotKey = $meta['relatedPivotKey'];

                    foreach ($models as $model) {
                        $ids[] = $model->{$localKey};
                    }
                    $ids = array_unique($ids);
                    if (empty($ids)) continue;

                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $query = "SELECT * FROM {$table} WHERE {$foreignPivotKey} IN ({$placeholders})";
                    $pivotRows = app()->db->query($query, $ids);
                    
                    $relatedIds = array_unique(array_column($pivotRows, $relatedPivotKey));
                    if (empty($relatedIds)) {
                        foreach ($models as $model) {
                            $model->relations[$relation] = [];
                        }
                        continue;
                    }

                    $relPlaceholders = implode(',', array_fill(0, count($relatedIds), '?'));
                    $tableName = Str::lower(Str::plural(class_basename($relatedClass)));
                    $relQuery = "SELECT * FROM {$tableName} WHERE id IN ({$relPlaceholders})";
                    $relatedRows = app()->db->query($relQuery, $relatedIds);
                    
                    $relatedModels = [];
                    foreach ($relatedRows as $row) {
                        $m = new $relatedClass;
                        foreach ($row as $k => $v) {
                            $m->{$k} = $v;
                        }
                        $relatedModels[$m->id] = $m;
                    }

                    foreach ($models as $model) {
                        $matched = [];
                        foreach ($pivotRows as $pRow) {
                            if ($pRow[$foreignPivotKey] == $model->{$localKey}) {
                                if (isset($relatedModels[$pRow[$relatedPivotKey]])) {
                                    $matched[] = $relatedModels[$pRow[$relatedPivotKey]];
                                }
                            }
                        }
                        $model->relations[$relation] = $matched;
                    }
                }
            }
        }

        self::$eagerLoading = false;
        self::$withRelations = [];

        return $models;
    }

    public static function create(array $attributes)
    {
        self::$instance = static::class;

        return app()->db->create($attributes);
    }

    public static function all()
    {
        self::$instance = static::class;

        $results = app()->db->read();
        return self::loadRelations($results);
    }

    public static function first()
    {
        self::$instance = static::class;

        $result = app()->db->read();
        $model = $result[0] ?? null;
        
        if ($model) {
            $models = self::loadRelations([$model]);
            return $models[0];
        }
        
        return null;
    }

    public static function find($id)
    {
        self::$instance = static::class;

        $result = app()->db->read('*', ['id', '=', $id]);
        $model = $result[0] ?? null;
        
        if ($model) {
            $models = self::loadRelations([$model]);
            return $models[0];
        }

        return null;
    }

    public static function delete($id)
    {
        self::$instance = static::class;

        return app()->db->delete($id);
    }

    public static function update($id, array $attributes)
    {
        self::$instance = static::class;

        return app()->db->update($id, $attributes);
    }

    public static function where($filter, $columns = '*')
    {
        self::$instance = static::class;

        $results = app()->db->read($columns, $filter);
        return self::loadRelations($results);
    }

    public static function getModel()
    {
        return self::$instance;
    }

    public static function getTableName()
    {
        return Str::lower(Str::plural(class_basename(self::$instance)));
    }

    public function hasOne($related, $foreignKey = null, $localKey = 'id')
    {
        $foreignKey = $foreignKey ?: Str::lower(class_basename($this)) . '_id';
        
        if (self::$eagerLoading) return ['type' => 'hasOne', 'related' => $related, 'foreignKey' => $foreignKey, 'localKey' => $localKey];
        
        $result = $related::where([$foreignKey, '=', $this->{$localKey}]);
        
        return $result[0] ?? null;
    }

    public function belongsTo($related, $foreignKey = null, $ownerKey = 'id')
    {
        $foreignKey = $foreignKey ?: Str::lower(class_basename($related)) . '_id';
        
        if (self::$eagerLoading) return ['type' => 'belongsTo', 'related' => $related, 'foreignKey' => $ownerKey, 'localKey' => $foreignKey];
        
        $result = $related::where([$ownerKey, '=', $this->{$foreignKey}]);
        
        return $result[0] ?? null;
    }

    public function hasMany($related, $foreignKey = null, $localKey = 'id')
    {
        $foreignKey = $foreignKey ?: Str::lower(class_basename($this)) . '_id';
        
        if (self::$eagerLoading) return ['type' => 'hasMany', 'related' => $related, 'foreignKey' => $foreignKey, 'localKey' => $localKey];
        
        return $related::where([$foreignKey, '=', $this->{$localKey}]);
    }

    public function belongsToMany($related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null, $parentKey = 'id')
    {
        $thisName = Str::lower(class_basename($this));
        $relatedName = Str::lower(class_basename($related));

        if (!$table) {
            $models = [$thisName, $relatedName];
            sort($models);
            $table = implode('_', $models);
        }

        $foreignPivotKey = $foreignPivotKey ?: $thisName . '_id';
        $relatedPivotKey = $relatedPivotKey ?: $relatedName . '_id';

        if (self::$eagerLoading) return [
            'type' => 'belongsToMany',
            'related' => $related,
            'table' => $table,
            'foreignPivotKey' => $foreignPivotKey,
            'relatedPivotKey' => $relatedPivotKey,
            'localKey' => $parentKey
        ];

        $pivotRows = app()->db->query("SELECT {$relatedPivotKey} FROM {$table} WHERE {$foreignPivotKey} = ?", [$this->{$parentKey}]);

        $results = [];
        foreach ($pivotRows as $row) {
            if ($model = $related::find($row[$relatedPivotKey])) {
                $results[] = $model;
            }
        }

        return $results;
    }
}