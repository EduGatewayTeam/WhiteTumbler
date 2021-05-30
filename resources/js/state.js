import { createStore } from 'redux';

let defaultState = {
    selectedRoomIndex: null
};

function reducer(state = defaultState, action) {
  switch(action.type) {
    case 'SET_SELECTED_ROOM_INDEX':
      console.log(action);
      return {
        ...state,
        selectedRoomIndex: action.data.selectedRoomIndex
      }
    default:
      return state
  }
}

export default createStore(reducer);