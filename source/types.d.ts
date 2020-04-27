
export  namespace ut{
  /**
   * событие на изменение маршрута броузера ( в пределах страницы )
  */
  export function onChangeLocation(callback:Function):void;  
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

export function DOM(selector:string, _parentDOM?:object = false):object;
export function DOMS(selector:string, _parentDOM?:object = false):object;
export function $D(...p):object;
export function parentDOM(selector:string, _parentDOM?:object = false):object;
export function childDOM(selector:string, _parentDOM?:object = false):object;

export interface iDevice{
  os:string;
  platform:string;
  mobile:boolean;
  isIE:boolean;
  browserName:string;
  chromium:boolean;
} 

export const dvc:iDevice;

interface iCoord{x:number,y:number};
interface iSize{x:number,y:number,w:number,h:number};

export namespace JX {
  /**jQuery object*/
  export const window:object; 
  /** координаты мыши 
   * @returns {iCoord} {x,y}
  */
  export function mouse():iCoord;
  
  /** размер экрана броузера
   * @returns {iSize} {x,y,w,h}
  */
  export function screen():iSize;
  /** определяет позицию DOM элемента относительно родительского */
  export function pos(dom:object,bound?:iCoord=undefined);
  /** определяет позицию DOM элемента относительно окна броузера */
  export function abs(dom:object,bound?:iCoord=undefined);

}; 

export namespace storage{
  export function get(name:string,param?:object={}):string;
  export function set(name:string,val:any,param?:object={}):void;
  export function exist(name:string,param?:object={}):boolean;
  export function del(name:string,param?:object={}):void;
}

export function flex(prop:string):object;
export function binds(_this:object,...funcName:string):void;