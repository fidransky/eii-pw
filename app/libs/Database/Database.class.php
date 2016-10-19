<?php
class Database {
    /*
    database connection identifier
    */
    private $connection;

    /*
    database table names prefix
    */
    private $prefix;
    
    /*
    debug mode (disabled by default)
    */
    private $debug = false;
    
    /*
    constructor
    */
    public function __construct($server, $username, $password, $name, $prefix = null, $debug = false) {
        $this->prefix = $prefix;
        $this->debug = $debug;

        $this->connection = @mysql_connect($server, $username, $password) or exit("<p class=\"error\">Database connection is not set.</p>");

        mysql_select_db($name, $this->connection);
        if (phpversion() >= "5.2.3") mysql_set_charset("utf8", $this->connection);
        else mysql_query("SET NAMES utf8 COLLATE utf8_general_ci", $this->connection);
    }

    /*
    destructor
    */
    public function __destruct() {
        if ($this->connection) {
            mysql_close($this->connection);
        }
    }

    /*
    set the debug mode
    */
    public function set_debug($debug) {
        $this->debug = $debug;
    }

    /*
    add the prefix to a table's name and escape the name containing an alias
    */
    private function construct_table_name($table) {
        return "`". $this->prefix . (strpos($table, " ") != false ? substr_replace($table, "`", strpos($table, " "), 0) : $table ."`");
    }

    /*
    construct the where condition
    */
    private function construct_where($where) {
        if (is_array($where)) {
            $return = $this->construct_where_recursive($where);

            if (is_array($return)) {
                $return = "(". implode(" AND ", $return) .")";
            }

            echo $return;
            return $return;
        }
        else {
            return $where;
        }
    }

    /*
    recursive function to go through the whole where array
    */
    private function construct_where_recursive($where) {
        $return = array();

        foreach ($where as $index => $value) {
            if (is_array($value)) {
                if ($index === "&") {
                    return "(". implode(" AND ", $this->construct_where_recursive($value)) .")";
                }
                elseif ($index === "|") {
                    return "(". implode(" OR ", $this->construct_where_recursive($value)) .")";
                }
                else {
                    $index = key($value);
                    if ($index !== "&" and $index !== "|") {
                        $value = array("&" => $value);
                    }

                    $return[] = $this->construct_where_recursive($value);
                }
            }
            else {
                $is_keyword = preg_match("/^IS/", $value);

                preg_match("/^<=|>=|!=|<|=|>|LIKE /", $value, $matches);
                if (count($matches) > 0) {
                    $value = str_replace($matches[0], "", $value);
                    $sign = rtrim($matches[0]);
                }
                else {
                    $sign = "=";
                }

                $return[] = $index ." ". ($is_keyword ? $value : ($sign ." '". (is_string($value) ? mysql_real_escape_string($value) : $value) ."'"));
            }
        }

        return $return;
    }

    /*
    retrieve data
    */
    public function get($columns, $table, $other = array(), $return = "assoc", $debug = false) {
        // construct a MySQL query
        $table = $this->construct_table_name($table);

        if (is_array($columns)) {
            $columns = implode(", ", $columns);
        }

        $join = null;
        if (array_key_exists("join", $other)) {
            if (is_array($other["join"])) {
                $join = array();

                foreach ($other["join"] as $join_table => $join_on) {
                    $join_table = $this->construct_table_name($join_table);
                    $join[] = $join_table ." ON ". $join_on;
                }
                
                $join = " LEFT JOIN ". implode(" LEFT JOIN ", $join);
            }
            elseif (is_string($other["join"])) {
                $join = " LEFT JOIN ". $other["join"];
            }
        }

        $where = null;
        if (array_key_exists("where", $other)) {
            $where = $this->construct_where($other["where"]);
            $where = " WHERE ". $where;
        }

        $group = null;
        if (array_key_exists("group", $other)) {
            $group = " GROUP BY ". $other["group"];
        }

        $having = null;
        if (array_key_exists("having", $other)) {
            $having = " HAVING ". $other["having"];
        }

        $order = null;
        if (array_key_exists("order", $other)) {
            if (is_array($other["order"])) {
                $order = " ORDER BY ". implode(", ", $other["order"]);
            }
            else {
                $order = " ORDER BY ". $other["order"];
            }
        }

        $limit = null;
        if (array_key_exists("limit", $other)) {
            if (is_array($other["limit"])) {
                $limit = " LIMIT ". implode(", ", $other["limit"]);
            }
            elseif (is_string($other["limit"])) {
                $limit = " LIMIT ". $other["limit"];
            }
        }

        // perform the query
        $query = "SELECT ".$columns." FROM ".$table.$join.$where.$group.$having.$order.$limit;
        $result = mysql_query($query, $this->connection);

        // error returned
        if (mysql_errno($this->connection) != 0) {
            // print debug information
            if ($this->debug or $debug) {
                echo("<pre>". $query ."</pre>");
                echo("<p class=\"error\">". mysql_error($this->connection) ."</p>");
            }

            return false;
        }

        // return the data/result
        if (mysql_num_rows($result) > 0) {
            if ($return == "assoc") {
                while ($array = mysql_fetch_assoc($result)) $data[] = $array;
            }
            else {
                while ($array = mysql_fetch_row($result)) $data[] = $array;
            }

            return $data;
        }

        return false;
    }

    /*
    update data
    */
    public function set($data, $table, $where = null, $debug = false) {
        if (!is_array($data)) return false;
        
        // construct a MySQL query
        $table = $this->construct_table_name($table);
        
        foreach ($data as $index => $value) {
            $value = $value === null ? "NULL" : "'". mysql_real_escape_string($value) ."'";

            if (empty($where)) {
                $indexes[] = "`". $index ."`";
                $values[] = $value;
            }
            else {
                $set[] = "`". $index ."`=". $value;
            }      
        }

        // perform the query
        if (!empty($where)) {
            $where = $this->construct_where($where);
            $query = "UPDATE ". $table ." SET ". implode(",", $set) ." WHERE ". $where;
        }
        else {
            $query = "INSERT INTO ". $table ."(". implode(",", $indexes) .") VALUES (". implode(",", $values) .")";
        }
        $result = mysql_query($query, $this->connection);

        // error returned
        if (mysql_errno($this->connection) != 0) {
            // print debug information
            if ($this->debug or $debug) {
                echo("<pre>". $query ."</pre>");
                echo("<p class=\"error\">". mysql_error($this->connection) ."</p>");
            }

            return false;
        }

        // return the result
        return $result;
    }

    /*
    remove data
    */
    public function remove($table, $where = null, $debug = false) {
        // construct a MySQL query
        $table = $this->construct_table_name($table);

        if (!empty($where)) {
            $where = $this->construct_where($where);
            $where = " WHERE ". $where;
        }

        // perform the query
        $query = "DELETE FROM ".$table.$where;
        $result = mysql_query($query, $this->connection);

        // error returned
        if (mysql_errno($this->connection) != 0) {
            // print debug information
            if ($this->debug or $debug) {
                echo("<pre>". $query ."</pre>");
                echo("<p class=\"error\">". mysql_error($this->connection) ."</p>");
            }

            return false;
        }

        // return the result
        return $result;
    }
}
?>