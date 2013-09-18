<?php
##################################################################################
# Program: MySQL Connection Class v0.1.1
#
# Copyright (C) 2005 Stephen Lake
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
##################################################################################


/*

This class is a mysql anstraction class used to wrap up native php functionality for mysql
queries. This program should be considered Beta quality.

This class is still under active development and functionality will be added with each
successive update. Currently the only place to download this file from is http://www.phpclasses.org

Other classes in development related to this one include PostgreSQL and ODBC.
*/

class MysqlConnector {
        var $conID; //connection ID to mysql
        var $host; //Host of the mysql database
        var $user; //mysql username
        var $pass; //mysql user password
        var $db; //mysql database name
        var $result; //mysql resultset array
        var $persist; //mysql persistence type

        function MysqlConnector($persist = 1) {
				// Connection options
       		$this->host='localhost';
                $this->user='root';
                $this->pass='password';
                $this->db='behindbarsdk';

                if($persist != 1) {
                	$this->connectDB();
                } else {
                	$this->PersistentConnectDB();
                }

        }

        /*
        	lets create a non-persistent connection to the database with the values supplied
			in the constructor
        */
        function connectDB() {
                $this->conID = @mysql_connect($this->host,$this->user,$this->pass) or die('Error connecting to the server '.mysql_error());
                mysql_select_db($this->db,$this->conID) or die('Error selecting database');
        }

        function PersistentConnectDB() {
        		$this->conID = @mysql_pconnect($this->host,$this->user,$this->pass) or die('Error connecting to the server '.mysql_error());
                mysql_select_db($this->db,$this->conID) or die('Error selecting database');
        }

        /*
			This executes the specified query and returns the results if any to us in the

		*/
        function execute_query($query) {
                $this->result = mysql_query($query,$this->conID) or die('Error performing query '.$query.'<br>Debug info is below:<br>'.$this->errormsg());
        }

        /*
			this function fetches each row in the result set
		*/
        function fetchRow($type = 1) {
        		if($type == 1) {
        			//return array as both number and associative indices
                	return mysql_fetch_array($this->result,MYSQL_BOTH);
                } elseif($type == 2) {
                	//return array as number indices
                	return mysql_fetch_array($this->result,MYSQL_NUM);
                } else {
                	//return array as associative indices
                	return mysql_fetch_array($this->result,MYSQL_ASSOC);
                }
        }

        /*
			This returns the number of rows found
		*/
        function numRows() {
                return mysql_num_rows($this->result);
        }

        /*
			This function returns the number of records that were affected by an
			insert,update or delete query
		*/
        function affectedRows() {
                return mysql_affected_rows($this->conID);
        }

        /*
			Returns the insertId of an insert query
		*/
        function getInsertID() {
                return mysql_insert_id($this->conID);
        }

        /*
			This returns the error message from a failed query
		*/
        function errorMsg() {
                return mysql_error();
        }

		/*
			This closes the database connection, however this is not expressly nessasry as
			PHP will close the non persistent connection anyway when the page is fully loaded.
		*/
		function closeDB() {
				return mysql_close();
		}
}

//require_once "includes/config.inc.php"; //Require the config file
//require_once "includes/mysql.class.php"; //Require the mysql connector class
//
//$mysql = new mysqlconnector(HOST,USER,PASS,DATABASE); //Create connection to database
//
////Create a sql query
//$sql = "SELECT * FROM example";
//
////Execute the query
//$mysql->execute_query($sql);
//
////Check to see if the resultset has records
//if($mysql->numRows() <= 0) {
//	print "no records found"; //no records were found
//} else {
//	// print out each row using associative indices
//	while($row = $mysql->fetchrow(3)) {
//		print $row['field1'] ."<br />";
//	}
//}
//
//$mysql->closeDB();
?>
