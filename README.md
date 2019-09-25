# Библиотека браузерных ф-ций 
## Установка
`npm i fmihel-lib -D`
## Набор ф-ций 
|name|result|notes|
|-----|-----|-----|
|DOM( selector [,parentDOM] )|DOM\|null| получить узел DOM используя selector |
|DOMS( selector [,parentDOM] )|[DOM,DOM,..]| получить массив DOM используя selector|
|$D( obj [,setObj] )|data\|undefined| получить/установить значение свойства data|
|defaulProps( ReactComponent , props)|undefined| установить свои свойства компоненту поверх наследуемых|
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
