# Библиотека браузерных ф-ций 
## Установка
`npm i fmihel-lib -D`
## Набор ф-ций 
|name|result|notes|
|-----|-----|-----|
|DOM( selector [,parentDOM] )|DOM\|null| получить узел DOM используя selector |
|DOMS( selector [,parentDOM] )|[DOM,DOM,..]| получить массив DOM используя selector|
|$D( obj [,setObj] )|data\|undefined| получить/установить значение свойства data|
|parentDOM( selector [,parentDOM] )|DOM\|null| получить родительский узел DOM используя selector = string \| DOM |
|childDOM( selector [,parentDOM] )|[DOM,DOM,...]\|null| получить дочерние узелы DOM используя selector = string \| DOM |

## JX
|name|result|notes|
|-----|-----|-----|
|window|jquery| ссылка на window |
|mouse()|{ x: int, y: int }| координаты мыши|
|screen()|{ x: 0, y: 0, w:int, h:int }| размер экрана браузера|


## ut
|name|result|notes|
|-----|-----|-----|
|random(min,max)|int| случайное чисо в интервале [min,max] |
|random_str(count)|string| случайная строка длиной count |
|get(obj,n1,n2,..n,default)|any|извлечение значения дочернего свойства Ex: ``` ut.get(a,'b','f',3,'c','find`t text')  <==>  a.b.f[3].c ```|

## react
|name|result|notes|
|-----|-----|-----|
|defaulProps( ReactComponent , props)|undefined| установить свои свойства компоненту поверх наследуемых|
|flex({...})|object|настройки для внешнего flex элемента|
|flexChild({...})|object|настройки для внутреннего flex элемента|
|binds(this,funcName1,funcName2,...)||привязка обработчиков к контексту выполнения|

