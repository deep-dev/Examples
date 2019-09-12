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
   _films_ - table with films,
   _categories_ - table with categories,
   _films_categories_ - linking table _films_ and _categories_.
   Needs to get all the films from a certain category.

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

При первом открытии страницы данные должны считываться и записываться в MYSQL таблицу. При последующих открытиях страницы данные должны выводиться из MYSQL таблицы игнорируя текущую ситуацию в корневой директории. Так называемый кэш в БД.
Внизу вывести ссылку “Обновить”, которая обновит данные о файлах в MYSQL таблице и на экране. Необходимо предоставить архив с файлами приложения и файлом readme.txt с описанием по
установке.