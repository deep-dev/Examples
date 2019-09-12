<?php
const DISPLAY_KEY = 0; // Display collumn key for items

try {

      $mydir = $_SERVER['DOCUMENT_ROOT']; //__DIR__
      $dir = new DirectoryIterator( $mydir );

      echo '<h4>' . $mydir . '</h4>' . "\n";
	  
	  $dirTable = new DirHtmlTable();

      while($dir->valid())
      {
          $file = $dir->current();
          
          if ( $file->isDot() )
          {
             $dir->next();
             continue;
          }

		  $dirTable->addRow(DISPLAY_KEY ? $dir->key() : null, 
                            $file->getFilename(), 
                            $file->isFile() ? $file->getSize() : ( $file->isDir() ? '[DIR]' : null ),
                            $file->isFile() ? $file->getExtension() : null,
                            date ("d.m.Y H:i:s.", $file->getMTime())
                           );
		  
          $dir->next();
      }

} catch (Exception $e) {
      echo get_class($e) . ": " . $e->getMessage();
}


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

    public function addRow($key, $name, $size = '&nbsp;', $type = '&nbsp;', $modified = '&nbsp;')
    {
      echo '<tr>' . "\n";
      echo (DISPLAY_KEY ? "<td>$key</td>" : '') .'<td>' . $name . '</td><td>' . $size . '</td><td>' . $type . '</td><td>' . $modified . "</td>\n";
      echo '</tr>' . "\n";
    }
	
    public function __destruct()
    {
        echo '</table>';
    }
}

?>