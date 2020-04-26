export  namespace ut{
    export function random(min:number,max:number):number;
    export function random_str(count:number):string;
    export function replaceAll(str:string, search:string, replaceTo:string):string;
    export function get(...args):any;
     /**
     * Возврат команды по ее алиасу(короткому описанию)
     * Ex: alias('direct',{direction:'direct'}) => direction
     * Ex: alias('direct',{direction:['direct','dir','d']}) => direction
     * Ex: alias('d',{direction:['direct','dir','d']}) => direction
     * Ex: alias('left',{direction:['direct','dir','d'],right:['r','noLeft']}) => left
     * Ex: alias('r',{direction:['direct','dir','d'],right:['r','noLeft']}) => right
     * @returns {string|Exception} full name of command
     */
    export function alias(alias:string,conform:object):string;
    /**
    * перебор по элементам или свойствам
     * При переборе по объекту в ф-цию func передается 5 параметра
     * ( значение свойства, имя свойства, весь объект, порядковый номер,список свойств )
     * @return {any} результа поиска
      */
    export function each(o:any[]|object, func:Function):any;
    export function translate(y:number, y1:number, y2:number, x1:number, x2:number):number;
}
