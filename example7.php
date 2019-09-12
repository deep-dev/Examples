<?php
const DISPLAY_KEY = 1; // Display collumn key for items
$refreshDB = 0;        // Refresh data in Database

if ( !$refreshDB )
{
    echo 'Read from DB';

    $dbh = connect_db();
	$myprocessor = new DirTableProcessor($dbh);
	
    $dirTable = new DirHtmlTable();
    exit;
}

try {
      $mydir = $_SERVER['DOCUMENT_ROOT']; //__DIR__
      $dir = new DirectoryIterator( $mydir );

      echo '<h4>' . $mydir . '</h4>' . "\n";

	  $dbh = connect_db();
	  $myprocessor = new DirTableProcessor($dbh);
	  //$myprocessor->execute();

      $dirTable = new DirHtmlTable();

      while($dir->valid())
      {
          $file = $dir->current();
          
          if ( $file->isDot() )
          {
             $dir->next();
             continue;
          }

          $values = [
                    'key' => DISPLAY_KEY ? $dir->key() : null, 
                    'name' => $file->getFilename(), 
                    'size' => $file->isFile() ? $file->getSize() : ( $file->isDir() ? '[DIR]' : null ), 
                    'type' => $file->isFile() ? $file->getExtension() : null, 
                    'modified' => date ("H:i:s d.m.Y", $file->getMTime())
          ];
          $dirTable->addRow( $values );
          if ( $refreshDB == 1 ) $myprocessor->addRecord( $values );
          $dir->next();
      }

      unset($dirTable);

      $mybutton = new RefreshButton();
} catch (Exception $e) {
      echo get_class($e) . ": " . $e->getMessage();
}


/* Connecting to db */
function connect_db() {
	$dsn = 'mysql:dbname=mytest;host=localhost';
	$user = 'root';
	$password = '';
	$driver = array(PDO :: MYSQL_ATTR_INIT_COMMAND => 'SET NAMES `utf8`'); 

	try {
		$db = new PDO($dsn, $user, $password, $driver);                 //create new PDO object for connecting db
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // Set error processing mode to ERRMODE_EXCEPTION
	} catch (PDOException $e) { 
		echo 'Error connection: '. $e->getCode() .'|'. $e->getMessage();    
		return false; 
	}
	return $db;
}

class DirTableProcessor
{
    private $dbh;                // PDO instance db connection
    private $selectStatement;    // PDOStatement query for select data
    private $currentId = 0;      // Current max id, already processed
	private $limit = 10;         // Size of processed data LIMIT

    public function __construct(\PDO $pdo)
    {
      $this->pdo = $pdo;
    }

    public function execute()
    {
      try {
			//$this->loadCurrentState();
			echo('Getting data from table table1 with id: ' . $this->currentId . "<br>\n");
            do {
				$stmt = $this->getSelectStatement($this->currentId);
				if ($stmt->execute()) {
                    $count = $stmt->rowCount();
                    echo($count . ' records found' . "<br>\n");
                    if ($count > 0) {
                        $records = $this->fetchRecord($stmt);
                        $stmt->closeCursor();
                        echo(count($records) . ' records found' . "<br>\n");
                        //$this->updateCount($records);
                    }
                } else {
                    throw new \RuntimeException('Failed to execute query '.$stmt->queryString.': '.$stmt->errorInfo());
                }
             } while ($count > 0);
      } catch (\Throwable $e) {
          echo('Exception: ' . $e->getMessage());
          echo('Exception trace: ' . PHP_EOL . $e->getTraceAsString());
      }
    }

    protected function getSelectStatement($currentId)
    {
      if ($this->selectStatement === null) {
          $sql = 'SELECT * FROM `table1` WHERE `id` > :currentId ORDER BY `id` ASC LIMIT :limit';
          $this->selectStatement = $this->pdo->prepare($sql);
      }
      $this->selectStatement->bindValue(':currentId', $currentId, \PDO::PARAM_INT);
      $this->selectStatement->bindValue(':limit', $this->limit, \PDO::PARAM_INT);
      return $this->selectStatement;
    }

	/* Processed records fetch */
    protected function fetchRecord(\PDOStatement $stmt)
    {
        $records = array();
		
        while (($row = $stmt->fetch(\PDO::FETCH_NUM)) !== false) {
            $this->currentId = (int) $row[0];
            if ( empty($row[1]) ) continue;
			echo '<br><pre>';
			print_r($row);
			echo '</pre><br>';
			
            $records = trim($row[1]);
            
        }
        return $records;
    }

	/* Processed add record */
    public function addRecord( $values )
    {
/*
INSERT INTO `mytest`.`table1` (`id`, `key`, `name`, `size`, `type`, `modified`) VALUES ('1', '2', 'Myname', '128', 'php', '2019-09-24 00:00:00');
*/
/*
        $values = [
                    'key' => $key,
                    'name' => $name,
                    'size' => $size,
                    'type' => $type,
                    'modified' => $modified
        ];
*/
        $sql = "INSERT INTO `table1` (`key`, `name`, `size`, `type`, `modified`) VALUES (:key, :name, :size, :type, :modified)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
    }
}

/**
 * Display HTML table with directory
 */
class DirHtmlTable
{
    public function __construct()
    {
      echo '<table cellpadding="5" cellspacing="0" border="1">' . "\n";
	  echo '<tr>' . "\n";
      echo DISPLAY_KEY ? '<th>' . '#' . '</th>' : '';
      echo '<th>' . 'File / Folder Name' . '</th>';
      echo '<th>' . 'Size' . '</th>'; // (for folders display: [DIR])
      echo '<th>' . 'Type' . '</th>'; // (display file extension, empty line for folders)
      echo '<th>' . 'Last Modified Date' . '</th>';
      echo '</tr>' . "\n";
    }

	/* Adding new row with data in HTML table */
    public function addRow( $values )
    {
      $key = $values['key'] ?? '&nbsp;'; 
      $name = $values['name'] ?? '&nbsp;';
      $size = $values['size'] ?? '&nbsp;';
      $type = $values['type'] ?? '&nbsp;';
      $modified = $values['modified'] ?? '&nbsp;';
      echo '<tr>' . "\n";
      echo (DISPLAY_KEY ? "<td>$key</td>" : '') .'<td>' . $name . '</td><td>' . $size . '</td><td>' . $type . '</td><td>' . $modified . "</td>\n";
      echo '</tr>' . "\n";
    }

    public function __destruct()
    {
        echo '</table>';
    }
}

/**
 * Display Refresh button on page
 */
class RefreshButton
{
    public function __construct()
    {
        echo '<p>';
        echo "<input type='button' name='Refresh' onclick=alert('Refreshing...'); value='Refresh'>\n";
        echo '</p>';
    }
}

?>