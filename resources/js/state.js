import { createStore } from "redux";

let defaultSchedule = [
    { dayname: "monday", even: null, odd: null },
    { dayname: "tuesday", even: null, odd: null },
    { dayname: "wednesday", even: null, odd: null },
    { dayname: "thursday", even: null, odd: null },
    { dayname: "friday", even: null, odd: null },
    { dayname: "saturday", even: null, odd: null }
];

let defaultState = {
    activeRoom: null,
    rooms: [],
    schedule: defaultSchedule,
    meetingsRecords: {
        "2d89adf7-1e9f-4ddc-a84f-d800d0b122a7" : 
        [
            {
                "name": "Экзамен по WEB",
                "preview": "https://bbb.is.sevsu.ru/presentation/efdb1dbe178961f2a78f26d3a3f1377fcbf237f5-1608202139757/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1608202139765/thumbnails/thumb-1.png",
                "duration": "1 h 23m",
                "usersCount": 12,
                "visibility": "public",
                "link": "https://bbb.is.sevsu.ru/playback/presentation/2.0/playback.html?meetingId=efdb1dbe178961f2a78f26d3a3f1377fcbf237f5-1608202139757",
            },
            {
                "name": "Лабораторная работа по WEB",
                "preview": "https://bbb.is.sevsu.ru/presentation/efdb1dbe178961f2a78f26d3a3f1377fcbf237f5-1608202139757/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1608202139765/thumbnails/thumb-1.png",
                "duration": "55m",
                "usersCount": 15,
                "visibility": "public",
                "link": "https://bbb.is.sevsu.ru/playback/presentation/2.0/playback.html?meetingId=efdb1dbe178961f2a78f26d3a3f1377fcbf237f5-1608202139757"
            },
            {
                "name": "Сдача курсовых работ",
                "preview": "https://bbb.is.sevsu.ru/presentation/efdb1dbe178961f2a78f26d3a3f1377fcbf237f5-1608202139757/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1608202139765/thumbnails/thumb-1.png",
                "duration": "2 h 3m",
                "usersCount": 7,
                "visibility": "public",
                "link": "https://bbb.is.sevsu.ru/playback/presentation/2.0/playback.html?meetingId=efdb1dbe178961f2a78f26d3a3f1377fcbf237f5-1608202139757"
            },
        ],
    }
};



function reducer(state = defaultState, action) {
    console.log(action);
    switch (action.type) {
        case "SET_ACTIVE_ROOM":
            // создается асинхронный запрос для получения всех записей и они добавляются в state
            return {
                ...state,
                activeRoom: { ...state.rooms[action.data.selectedRoomIndex]}
            };
        case "SET_ROOMS":
            // устанавливаем комнаты в общий стейт
            return {
                ...state,
                rooms: [...action.data.rooms]
            };
        case "SET_ROOM_SCHEDULE":
            return {
                ...state,
                schedule: [...action.data.schedule],
            };
        case 'UPDATE_ROOM_SCHEDULE':
            return {
                ...state,
                rooms: [ ...action.data.rooms],
                activeRoom: { ...action.data.activeRoom }
            };
        case 'SET_DEFAULT_SCHEDULE':
            console.log('SET_DEFAULT_SCHEDULE'.toLowerCase());
            return{
                ...state,
                schedule: [...defaultSchedule]
            }
        default:
            return state;
    }
}

export default createStore(reducer);
