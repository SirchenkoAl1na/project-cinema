<?php
namespace App;

use PDO, PDOException,Exception;

$conn = DB::connect();
class DB
{
    private static string $dbname='cinema';
    static function connect()
    {
        try {
            $dbname=self::$dbname;
            $pdo = new PDO("mysql:host=localhost", 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $dbname = "`".str_replace("`","``",$dbname)."`";
            $pdo->query("CREATE DATABASE IF NOT EXISTS $dbname");
            $pdo->query("use $dbname");
            return $pdo;
        } catch (PDOException $e) {
            Data::Error('Помилка підключення до бази даних: ' . $e->getMessage());
        }
    }
    static function select($table_name, $select = '*', $where = '', $order = '', $limit = '')
    {
        $db = DB::connect();
        $query = "SELECT $select FROM $table_name";
        if ($where) {
            $query .= " WHERE $where";
        }
        if ($order) {
            $query .= " ORDER BY $order";
        }
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        try {
            // echo $query;
            $result = $db->query($query);
            if ($result === false) {
                return [];
            }
            $items = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row;
            }
            return $items;
        } catch (Exception $e) {
            Data::Error($e,$query);
        }
    }
    
    static function selectOne($table_name, $select = '*', $where = '', $order = '')
    {
        $db = DB::connect();
        $query = "SELECT $select FROM $table_name";
        if ($where) $query .= " WHERE $where";
        if ($order) $query .= " ORDER BY $order";
        
        $query .= " LIMIT 1";

        try {
            $result = $db->query($query);
            if ($result === false) {
                return [];
            }
            $items = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row;
            }
            return !empty($items)?$items[0]:null;
        } catch (Exception $e) {
            Data::Error($e,$query);
        }
    }
    static function query(string $query)
    {
        try {
            $db = DB::connect();
            $result = $db->query($query);
            return $result;
        } catch (PDOException $e) {
            Data::Error($e);
        }

    }
    static function selectByQuery(string $query)
    {
        $db = DB::connect();
        try {
            $result = $db->query($query);
            if ($result === false) {
                return [];
            }
            $items = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row; 
            }
            return $items;
        } catch (PDOException $e) {
            Data::Error($e,$query);
        }

    }

    static function insert($table_name, $data)
    {
        try {
            $db = DB::connect();
            $keys = array_keys($data);
            $values = array_values($data);
            $query = "INSERT INTO $table_name (" . implode(',', $keys) . ') VALUES (' . implode(',', array_fill(0, count($values), '?')) . ')';
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute($values);
            return $result;
        } catch (PDOException $e) {
            Data::Error($e);
        
        }
    }

    static public function update($table_name, $condition, $data)
    {
        $db = DB::connect();
        try {
            $keys = array_keys($data);
            $values = array_values($data);
            $query = "UPDATE $table_name SET ";
            foreach ($keys as $key) {
                $query .= "$key=?,";
            }
            $query = rtrim($query, ',');
            $query .= " WHERE $condition";
            $stmt = $db->prepare($query);
            $result = $stmt->execute($values);
        } catch (PDOException $e) {
            Data::Error($e);
        
        }
        return $result;
    }

    static public function delete($table_name, $condition)
    {
        $db = DB::connect();
        try {
            $query = "DELETE FROM $table_name WHERE $condition";
            // echo $query;
            $stmt = $db->prepare($query);
            $result = $stmt->execute();
        } catch (PDOException $e) {
            Data::Error($e);
        }
        
        return $result;
    }

    static function lastInsertId()
    {
        $db = DB::connect();
        try {
            $res = $db->lastInsertId();
        } catch (PDOException $e) {
            Data::Error($e);
        }
        return $res;
    }



}