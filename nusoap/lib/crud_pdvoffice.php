<?php
/*
 * File Name: Database.php
 * Date: November 18, 2008
 * Author: Angelo Rodrigues
 * Description: Contains database connection, result
 *              Management functions, input validation
 *
 *              All functions return true if completed
 *              successfully and false if an error
 *              occurred
 *
 */
class Database
{

    /*
     * Edit the following variables
     */
    private $db_host = 'mysql.simpletpv.com';     // Database Host
    private $db_user = 'simpletpv_diva';          // Username
    private $db_pass = 'd1v4d1v4@';          // Password
    private $db_name = 'simpletpv_diva';          // Database
    /*
     * End edit
     */

    private $con = false;               // Checks to see if the connection is active
    private $result = array();          // Results that are returned from the query

    /*
     * Connects to the database, only one connection
     * allowed
     */
    public function connect()
    {
        if(!$this->con)
        {
            $myconn = @mysql_connect($this->db_host,$this->db_user,$this->db_pass);
            if($myconn)
            {
                $seldb = @mysql_select_db($this->db_name,$myconn);
                if($seldb)
                {
                    $this->con = true;
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }

    /*
    * Changes the new database, sets all current results
    * to null
    */
    public function setDatabase($name)
    {
        if($this->con)
        {
            if(@mysql_close())
            {
                $this->con = false;
                $this->results = null;
                $this->db_name = $name;
                $this->connect();
            }
        }

    }

    /*
    * Checks to see if the table exists when performing
    * queries
    */
    private function tableExists($table)
    {
        $tablesInDb = @mysql_query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
        if($tablesInDb)
        {
            if(mysql_num_rows($tablesInDb)==1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /*
    * Selects information from the database.
    * Required: table (the name of the table)
    * Optional: rows (the columns requested, separated by commas)
    *           where (column = value as a string)
    *           order (column DIRECTION as a string)
    */
    public function select($table, $rows = '*', $where = null, $order = null)
    {
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $q .= ' WHERE '.$where;
        if($order != null)
            $q .= ' ORDER BY '.$order;
        $query = @mysql_query($q);
        if($query)
        {
            $this->numResults = mysql_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysql_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    // Sanitizes keys so only alphavalues are allowed
                    if(!is_int($key[$x]))
                    {
                        if(mysql_num_rows($query) > 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysql_num_rows($query) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function select_directo($sql)
    {
        $query = @mysql_query($sql);
        if($query)
        {
            $this->numResults = mysql_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysql_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    // Sanitizes keys so only alphavalues are allowed
                    if(!is_int($key[$x]))
                    {
                        if(mysql_num_rows($query) > 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysql_num_rows($query) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
    * Insert values into the table
    * Required: table (the name of the table)
    *           values (the values to be inserted)
    * Optional: rows (if values don't match the number of rows)
    */
    public function insert($table,$values,$rows = null, $duplicate = null)
    {
        
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($rows != null)
            {
                $insert .= ' ('.$rows.')';
            }

            for($i = 0; $i < count($values); $i++)
            {
                if(is_string($values[$i]))
                    
                    if($values[$i] == "now()"){
                        $values[$i] = $values[$i];
                    }else{
                        $values[$i] = '"'.$values[$i].'"';
                    }
                    
                    
            }
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';
            
            if($duplicate != null)
            {
                $insert .= ' ON DUPLICATE KEY UPDATE '.$duplicate;
            }
            

            $ins = @mysql_query($insert);
            if($ins)
            {
                $this->result = @mysql_insert_id();
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    public function insert_bulk($table,$values,$rows = null, $duplicate = null)
    {
        
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($rows != null)
            {
                $insert .= ' ('.$rows.')';
            }

            $insert .= ' VALUES '.$values;
            
            if($duplicate != null)
            {
                $insert .= ' ON DUPLICATE KEY UPDATE '.$duplicate;
            }
            

            $ins = @mysql_query($insert);
            if($ins)
            {
                $this->result = @mysql_insert_id();
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /*
    * Deletes table or records where condition is true
    * Required: table (the name of the table)
    * Optional: where (condition [column =  value])
    */
    public function delete($table,$where = null)
    {
        if($this->tableExists($table))
        {
            if($where == null)
            {
                $delete = 'DELETE '.$table;
            }
            else
            {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where;
            }
            $del = @mysql_query($delete);
            if($del)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Updates the database with the values sent
     * Required: table (the name of the table to be updated
     *           rows (the rows/values in a key/value array
     *           where (the row/condition in an array (row,condition) )
     */
    public function update($table,$rows,$where)
    {
        if($this->tableExists($table))
        {
            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            for($i = 0; $i < count($where); $i++)
            {
                if($i%2 != 0)
                {
                    if(is_string($where[$i]))
                    {
                        if(($i+1) != null)
                            $where[$i] = '"'.$where[$i].'" AND ';
                        else
                            $where[$i] = '"'.$where[$i].'"';
                    }
                }
            }
            $where = implode('',$where);


            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($rows);
            for($i = 0; $i < count($rows); $i++)
            {
                if(is_string($rows[$keys[$i]]))
                {
                    $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
                }
                else
                {
                    $update .= $keys[$i].'='.$rows[$keys[$i]];
                }

                // Parse to add commas
                if($i != count($rows)-1)
                {
                    $update .= ',';
                }
            }
            $update .= ' WHERE '.$where;
            $query = @mysql_query($update);
            
            if($query)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
    * Returns the result set
    */
    public function getResult()
    {
        return $this->result;
    }
}
?>