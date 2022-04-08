<?php
/**
 *
 */
class Database
{
  private static $_instance = null;
  private $_pdo,
          $_query,
          $_error = false,
          $_results,
          $_count = 0;

          //create construct to connect database;
      private  function  __construct(){
          try {
            $this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));

          } catch (PDOException $e) {
            die($e->getMessage());
          }

        }
// create get instance method
        public static function getInstance()
        {
          if(!isset(self::$_instance)){
            self::$_instance = new Database();
          }
          return self::$_instance;
        }
        // create query method
        public  function query($sql, $params = array())
        {
          $this->_error = false;
          if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
              foreach ($params as $param) {
              $this->_query->bindValue($x, $param);
              $x++;
              }
            }
            if ($this->_query->execute()) {
              $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
              $this->_count = $this->_query->rowCount();
            }else{
              $this->_error = true;
            }
          }

          return $this;
        }
        //action method
        public function action($action, $table, $where = array())
        {
          if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
              $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

              if (!$this->query($sql, array($value))->error()) {
                return $this;
              }
            }
          }
          return false;
        }

//insert method
      public function insert($table, $fields = array())
      {
          $keys = array_keys($fields);
          $values = '';
          $x = 1;
          foreach ($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
              $values .= ', ';
            }
            $x++;
          }


          $sql = "INSERT INTO {$table} (`".implode('`, `',$keys)."`)  VALUES ({$values})";
//            print_r($sql);
//            die();
          if (!$this->query($sql, $fields)->error()) {
            return true;
          }

        return false;
      }

      // update method
      public function update($table,$selector, $id, $fields)
      {
        $set = '';
        $x = 1;
        foreach ($fields as $name => $value) {
          $set .= "{$name} = ?";
          if ($x < count($fields)) {
            $set .= ', ';
          }
          $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE $selector = $id ";
//          print_r($sql);
//            die();
        if (!$this->query($sql, $fields)->error()) {
          return true;
        }
        return false;
      }
        //gets method
        public function get($table, $where)
        {
          return $this->action('SELECT *', $table, $where);
        }
        //get method
        public function getAll($table, $selector, $val)
        {
          $sql = "SELECT * FROM {$table} WHERE {$selector} = {$val} ORDER BY id DESC";
          $this->query($sql);


        }
        // delete method
        public function delete($table, $where)
        {
          return $this->action('DELETE', $table, $where);

        }
        // count method
        public function count()
        {
         return $this->_count;
        }
        // get first method

        //create error method
    public function error()
    {
      return $this->_error;
    }

    public function results()
    {
      return $this->_results;
    }

    public function first()
    {
      return $this->results()[0];
    }

//this is cool to do more fetch
    public function action2($userd, $name)
    {
        $sql = "SELECT * FROM test WHERE username = '$userd' AND name = '$name'";
      if ($this->query($sql)) {
        $result = $this->first();
        return $result;
      }


    }


//this is cool to do more fetch
    public function action3($user_email)
    {
    $sql = "SELECT * FROM codeChat_users WHERE user_email = '$user_email'";
      if ($this->query($sql)) {
        $result = $this->first();
        return $result;
      }


    }

  // multiple login method
// public function login($user_access)
//     {
//    $sql = "SELECT * FROM codeChat_users WHERE  user_username = '$user_access' OR  user_phoneNo = '$user_access' OR  user_email = '$user_access' AND deleted = 0";
//       if ($this->query($sql)) {
//         $result = $this->first();
//         return $result;

// //       }


//     }

}
