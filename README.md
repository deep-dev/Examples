### This is some examples of code.

### 1. It's possible in PHP and how it's can be implemented, if possible:
```php
   $obj = new Building();
   $obj['name'] = 'Main tower';
   $obj['flats'] = 100;
   $obj->save();
```
   **Yes it's possible!**
   
 - Sources for Example 1 in file:
	_example1.php_

### 2. It's the following possible in PHP?
```php
    $dateTime = new DateTime();
    echo (clone $dateTime)->format( 'Y');
```
   **Yes it's possible!**
   
 - Sources for Example 2 in file:
	_example2.php_
	
### 3. How to check if the date stored in a variable $str matches for specific format? 
###    Use the format description like in the function php date(). Format description example:
```php
   $format = 'd.m.Y';
   $format = 'H.i';
```
 - Source for Example 3 in file:
	_example3.php_
	
### 4. How to get json response in Yii2?
```php
   Insert in the controller's action somewhere before return:
   \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
```

### 5. How can “many-to-many” relationships be implemented in Yii 2?
    
    There are tables:
    - _films_ - table with films,
    - _categories_ - table with categories,
    - _films_categories_ - linking table _films_ and _categories_.
    
	Needs to get all the films from a certain category.
	
```php
   class FilmCategory
   {
      public function getFilms()
	  {
         return $this->hasMany(Films::className(), ['film_id' => 'film_id'])->viaTable('films_categories', ['category_id' => 'category_id']);
      }
   }
```

### 6. There is a table with columns a and b, both columns are of type INT. The request is:
```sql
   "SELECT a, COUNT(*) FROM t GROUP BY a"
```
   How to change this query so that unique “a” values are displayed that appear in the table more than 2 times?
   
```sql
   "SELECT a, COUNT(*) AS i FROM t GROUP BY a HAVING i > 2"
```

### 7. Write a simple web application that displays a table listing files in the host root directory (DOCUMENT_ROOT).

    Table columns:
    - File / Folder Name;
    - Size (for folders display: [DIR]);
    - Type (display file extension, empty line for folders);
    - Last Modified Date.

When you first open the page, the data should be read and written to the MYSQL table. The next time you open the page, data should be output from the MYSQL table ignoring the current situation in the root directory. The so-called cache in the database.
At the bottom, display the “Refresh” link, which will update files information in the MYSQL table and on the screen. You must provide an archive with application files and a readme.txt file with installation process descriptions.