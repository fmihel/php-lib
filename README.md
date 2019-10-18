# Библиотека ф-ций javascript (и для серверной и для броузерной части)
## Установка
`npm i fmihel-lib -D`
## Набор ф-ций 

## ut
|name|result|notes|
|-----|-----|-----|
|random(min,max)|int| случайное чисо в интервале [min,max] |
|random_str(count)|string| случайная строка длиной count |
|get(obj,n1,n2,..n,default)|any|извлечение значения дочернего свойства Ex: ``` ut.get(a,'b','f',3,'c','find`t text')  <==>  a.b.f[3].c ```|
