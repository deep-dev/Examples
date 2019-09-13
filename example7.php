<?php
const DISPLAY_KEY = 1;              // Display collumn key for items
$mydir = $_SERVER['DOCUMENT_ROOT']; // Directory for display DOCUMENT_ROOT or __DIR__ for example

if( isset($_POST['refresh']) && $_POST['refresh'] == 1 )
    $refreshDB = 1;    // Refresh data in Database
else
    $refreshDB = 0;    // Read data from Database

try {
      $dbh = DB::getInstance();
      $myprocessor = new DirTableProcessor($dbh);

      if ( !$refreshDB )
      {
          echo '<h4>Read from DB</h4>' . "\n";
          $dirTable = new DirHtmlTable();
          if ( $myprocessor->execute() == 0 )
          {
              echo '<p>First time running... DB is empty</p>';
              $refreshDB = -1; // First time running
          } else {
              unset($dirTable);
              $mybutton = new RefreshButton();
              exit;
          }
      }

      $dir = new DirectoryIterator( $mydir );

      echo '<h4>' . $mydir . '</h4>' . "\n";

      if ( $refreshDB == -1 ) $refreshDB = 1; // First time running
	  else
	  {
         $myprocessor->clearTable();
         $dirTable = new DirHtmlTable();
      }

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

class DB
{
    private const DB_HOSTNAME = 'localhost';
    private const DB_DATABASE = 'mytest';
    private const DB_USERNAME = 'root';
    private const DB_PASSWORD = '';
    private const driver = array(PDO :: MYSQL_ATTR_INIT_COMMAND => 'SET NAMES `utf8`'); 
    private static $instance = NULL;   // Declare instance

    private function __construct() {
    }

    /**
     * Return DB instance or create intitial connection
     * @return object (PDO)
     * @access public
     */
    public static function getInstance() {
        if (!self::$instance) {
            try {
                  self::$instance = new PDO("mysql:host=".self::DB_HOSTNAME.";dbname=".self::DB_DATABASE, self::DB_USERNAME, self::DB_PASSWORD, self::driver);  //create new PDO object for connecting db
                  self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error processing mode to ERRMODE_EXCEPTION
            } catch (PDOException $e) { 
                  echo 'Error connection: '. $e->getCode() .'|'. $e->getMessage();    
                  return NULL; 
            }
        }

        return self::$instance;
    }

    public static function close() {
        if (self::$instance) {
            self::$instance = null;
        }
    }

    /* Like the constructor, we make __clone private, so nobody can clone the instance */
    private function __clone() {
    }
}

class DirTableProcessor
{
    private $pdo;                // PDO instance db connection
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
         do {
				$stmt = $this->getSelectStatement($this->currentId); // Getting data from table table1 with id: $this->currentId
				if ($stmt->execute()) {
                    $count = $stmt->rowCount(); // records found
                    if ($count > 0) {
                        $records = $this->fetchRecord($stmt);
                        $stmt->closeCursor();
                    } else {
                        if( $this->currentId == 0 ) return 0;
                    }
                } else {
                    throw new \RuntimeException('Failed to execute query '.$stmt->queryString.': '.$stmt->errorInfo());
                }
             } while ($count > 0);
      } catch (\Throwable $e) {
          echo('Exception: ' . $e->getMessage());
          echo('Exception trace: ' . PHP_EOL . $e->getTraceAsString());
      }
	  return 1;
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
		
        while (($row = $stmt->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $this->currentId = (int) $row[id];
            DirHtmlTable::addRow( $row );
        }
    }

    /* Processed add record */
    public function addRecord( $values )
    {
        $sql = "INSERT INTO `table1` (`key`, `name`, `size`, `type`, `modified`) VALUES (:key, :name, :size, :type, :modified)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
    }

    public function clearTable()
    {
        $sql = "DELETE FROM `table1` WHERE 1";
        $deletedRows = $this->pdo->exec($sql);
        return $deletedRows;
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
    static public function addRow( $values )
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
        echo '<form action="example7.php" method="POST">';
        echo '<input type="hidden" name="refresh" value="1" />';
        echo "<input type='submit' name='Refresh' onclick=alert('Refreshing...'); value='Refresh'>";
        echo '</form>';
        echo '</p>';
    }
}

?>