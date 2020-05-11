# Библиотека браузерных ф-ций 
## Установка
`npm i fmihel-browser-lib -D`
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
|visible(DOM,any?,string?)|boolean| определение или уставновка видимости DOM|



## react
|name|result|notes|
|-----|-----|-----|
|defaulProps( ReactComponent , props)|undefined| установить свои свойства компоненту поверх наследуемых|
|flex({...})|object|настройки для внешнего flex элемента|
|flexChild({...})|object|настройки для внутреннего flex элемента|
|binds(this,funcName1,funcName2,...)||привязка обработчиков к контексту выполнения|


## storage
Работа с local storage,sesssion storage и cookie
|name|result|notes|
|-----|-----|-----|
|storage.set(name,value[,param])|undefined|сохранить переменную на клиенте (по умолчанию local Storage)|
|storage.get(name[,param])|any|получить сохраненну переменную  (по умолчанию local Storage)|
|storage.exist(name[,param])|bool|признак наличия переменной  (по умолчанию local Storage)|
|storage.del(name[,param])|unefined|удаление переменной  (по умолчанию local Storage)|

param - настройки для storage
|name|mean|notes|
|-----|-----|-----|
|type|'local','session','cookie'|указывает с каким хранилищем работает текущая ф-ция, по умолчанию все работают с local, глобально ожно настроить если указать storage.param.type='...'|
|default| any | значение по умолчанию, для ф-ции get в случае если переменная не определена|
|cookie_expires|num| время жизни переменной cookie при type = 'cookie'|
|cookie_path|string| путь переменной cookie при type = 'cookie'|
|cookie_domen|string| домен переменной cookie при type = 'cookie'|


