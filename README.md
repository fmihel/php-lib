# php-lib v3.7.0
 
 ```composer require fmihel/php-lib```

[1. Str - работа со строками](#Str)<br/>
[2. Common - разное](#Common)<br/>
[3. Dir - работа с файлами и каталогами](#Dir)<br/>
[3. Events - создание коллекций событий](#Events)<br/>
[4. Arr - работа с массивами](#Arr)<br/>
[4. Type - работа с типами](#Type)<br/>


---
## Str
```php
use fmihel\lib\Str;
``` 
### property

|name|args|result|notes|
|----|----|-----|-----|
|random|(count)|string|случайная строка длиной $count (начинается с буквы, всегда загланые буквы и цифры)|
||count|int|кол-во символов на выходе|
|translit|(str,callback=null)|string|транслитирация для строки. заменятся только кирилические символы, для др символов можно задать спец ф-цию callback,которая должна вернуть значение для переданного символа <br>Ex: trasnlit('йцрувцр839wkjd');<br>Ex: trasnlit('path/путь\','fmihel\lib\Str::TRANSLIT_TO_URL');<br>Ex: trasnlit('abcрусс',function($s){return '*';});|
||str|string| строка к транслитерации
||callback=null|function\|null|ф-ция для преобразования нетраслитирируемых символов|
|mb_trim|(str)|string| мультибайтная версия trim|
||str|string| входная строка |

---
## Common
```php
use fmihel\lib\Common;
``` 
### property
|name|args|result|notes|
|----|----|-----|-----|
|get|($var,$name1,$name2,...,$default)|any|возвращает значение свойства объекта, который может быть вложен в другой объект<br> Ex: $var=[A=>[B=>[73,43,89]]];<br> Common::get($var,'A','B',2,null) = 89|
||$var|object \|\| array |переменная содержащаяя объект или массив|
||$name1,$name2..|string \|\| int |имя свойства объетка или порядковый номер в массиве|
||$default|any|значение, если в цепочке объектов не окажется объекта  с именем или индексом $name|




---
## Dir
```php
use fmihel\lib\Dir;
``` 
### property
|method|args|result|notes|
|----|----|-----|-----|


---
## Events
```php
use fmihel\lib\Events;
``` 
### property
|method|args|result|notes|
|----|----|-----|-----|

---
## Arr
```php
use fmihel\lib\Arr;
``` 
### property
|method|args|result|notes|
|----|----|-----|-----|

---
## Type
```php
use fmihel\lib\Type;
``` 
### property
|method|args|result|notes|
|----|----|-----|-----|






