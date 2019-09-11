### This is some examples of code.

### 1. It's possible in PHP and how it's can be implemented, if possible:
```php
	 $obj = new Building();
     $obj['name'] = 'Main tower';
     $obj['flats'] = 100;
     $obj->save();
```
   Yes it's possible!
   
 - Sources for Example 1 in file:
	_example1.php_

### 2. It's the following possible in PHP?
```php
	$dateTime = new DateTime();
	echo (clone $dateTime)->format( 'Y');
```
   Yes it's possible!
   
 - Sources for Example 2 in file:
	_example2.php_
	
### 3. Как проверить соответствует ли дата, хранимая в переменной $str, определенному формату? 
###    Используем описание формата такое же как в функции php date(). Пример описания формата:
```php
    $format = 'd.m.Y';
	$format = 'H.i';
```
 - Source for Example 3 in file:
	_example3.php_
	
### 4. Как получить ответ в формате json в Yii 2?

### 5. Каким образом можно реализовать связь “многие ко многим” в Yii 2?
Есть таблицы: films - таблица с фильмами, categories - таблица с категориями, films_categories - связь таблиц films и categories. Нужно получить все фильмы из определенной категории.

### 6. Есть таблица с колонками a и b, обе колонки типа INT. Дан запрос "select a, count(*) from t group by a". Как изменить этот запрос, чтобы вывелись уникальные значения “a” которые встречаются в таблице более 2х раз?

### 7. Написать простое веб-приложение, которое выводит таблицу со списком файлов вкорневой директории хоста (DOCUMENT_ROOT).

Столбцы таблицы:
• Название файла/папки;
• Размер (для папок выводить [DIR]);
• Тип (вывести расширение файла, для папок пустая строка);
• Дата последней модификации.

При первом открытии страницы данные должны считываться и записываться в MYSQL таблицу. При последующих открытиях страницы данные должны выводиться из MYSQL таблицы игнорируя текущую ситуацию в корневой директории. Так называемый кэш в БД.
Внизу вывести ссылку “Обновить”, которая обновит данные о файлах в MYSQL таблице и на экране. Необходимо предоставить архив с файлами приложения и файлом readme.txt с описанием по
установке.