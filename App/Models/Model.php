<?php

namespace App\Models;
use App\DB;

abstract class Model
{
    protected static $table;
    public static function all($params = [],$select="*",$joins="")
    {
        if (is_null(static::$table)) {
            throw new \Exception("Table name is not defined.");
        }
        else if(is_array($params) && count($params)>0){
            $query="";
            try{
                $order_by='';
                $where='';
                $pagination_page=isset($params['pagination_page']) ? $params['pagination_page'] : null;
                $pagination_limit=isset($params['pagination_limit']) ? $params['pagination_limit'] : null;
                
                $whereClauses = [];
                if (isset($params['search'])) {
                    $whereClauses[] = $params['search'];
                }
                if (isset($params['filter'])) {
                    $whereClauses[] = $params['filter'];
                }
                $where = count($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
                $pagination='';
                if(!is_null($pagination_page) && !is_null($pagination_limit)){
                    $offset=($pagination_page-1)*$pagination_limit;
                    $pagination="LIMIT ".$pagination_limit." OFFSET ".$offset;
                }
                if (isset($params['sort'])) {
                    $order_by = 'ORDER BY ' . $params['sort'];
                }       
                $query= "SELECT ".$select." FROM ".static::$table." ".$joins." ".$where." ".$order_by." ".$pagination." ;";
                
                return DB::selectByQuery($query);
            }
            catch(Exception $e){
                echo $e;
            }

        }
        else return DB::select(static::$table);
    }


    public static function count(){
        return DB::selectByQuery("SELECT COUNT(id) as count FROM ".static::$table.";")[0]['count'];
    }

    public static function where(string $conditions,$order=null,$limit=null)
    {
        return DB::select(static::$table,"*",$conditions,$order,$limit);
    }

    public static function find($id)
    {
        return DB::selectOne(static::$table, "*", "id=". $id);

    }
    public static function create(array $data)
    {
        DB::insert(static::$table, $data);
        return DB::selectOne(static::$table,'*','','id desc');
    }
    public static function update(string $condition,array $data)
    {
        return DB::update(static::$table, $condition, $data);
    }
    public static function delete(string $condition){
        return DB::delete(static::$table, $condition);
    }

}
