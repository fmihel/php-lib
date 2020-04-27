import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';
import Reducers from './reducers';
import Data from './data';
import Storing from './storing';

class Redux {
    constructor(init, storing = undefined) {
        this.init = init;
        this.reducers = new Reducers(init);
        this.store = createStore((store = init, action) => this.reducers.handler(store, action), applyMiddleware(thunk));
        this.data = new Data(init);

        this.private = {
            storing: storing || new Storing(init),
        };
        this.actions = {};
    }

    /**
     * утилита для изменения состояния
     * @param {object} state
     */
    change(state) {
        this.private.storing.setState(state);
        return this.private.storing;
    }

    /**
     * одновременное добавление редюсера и действия
     * описание reducerObject и actionObject см в addAction и addReducer
     */
    add(reducerObject, actionObject) {
        if (!reducerObject || !actionObject) {
            throw Error('both args redux.add must be not null');
        }
        this.addAction(actionObject);
        this.addReducer(reducerObject);
    }

    /**
     * добавление редюсера
     * @param {object} объект структуры { is , reducer } is-функция, reducer-ф-ция редюсера
     * is - function(action) - ф-ция возвращает true если action.type соотвествует reducer
     * reducer - function(state,action) - стандартная ф-ция для редюсера
    */
    addReducer(reducerObject) {
        this.reducers.add(reducerObject);
    }

    /**
     * добавление action действия
     *
     * @param {object} - actionObject = {funcName:action}
     * funcName - имя по которому можно будет вызывать действие используя redux.actions.funcName
     * если имя
     * @return {action} - если действия добавлено много, то вернет первое
    */
    addAction(actionObject) {
        let result;
        const names = Object.keys(actionObject);
        names.forEach((name) => {
            if (name in this.actions) {
                throw Error(`action "${name}" already exists in redux.actions list`);
            }
            this.actions[name] = actionObject[name];
            if (!result) result = actionObject[name];
        });
        return result;
    }
}

export default {
    Redux,
    Storing,
};