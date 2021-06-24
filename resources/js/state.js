import { createStore } from "redux";

// значение расписания по умолчанию
let defaultSchedule = [
    { dayname: "monday", even: null, odd: null },
    { dayname: "tuesday", even: null, odd: null },
    { dayname: "wednesday", even: null, odd: null },
    { dayname: "thursday", even: null, odd: null },
    { dayname: "friday", even: null, odd: null },
    { dayname: "saturday", even: null, odd: null }
];

// состояние приложения по умолчанию
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
    console.log('action: ', action);
    switch (action.type) {
        // установка акстивной комнаты
        case "SET_ACTIVE_ROOM":
            return {
                ...state,
                activeRoom: { ...action.data.activeRoom }
            };
        // установка комнат
        case "SET_ROOMS":
            return {
                ...state,
                rooms: [...action.data.rooms]
            };
        // установка расписания комнаты
        case "SET_ROOM_SCHEDULE":
            return {
                ...state,
                schedule: [...action.data.schedule],
            };
        // обновление расписания комнаты
        case 'UPDATE_ROOM_SCHEDULE':
            return {
                ...state,
                rooms: [ ...action.data.rooms],
                activeRoom: { ...action.data.activeRoom }
            };
        // установка начального расписания
        case 'SET_DEFAULT_SCHEDULE':
            return {
                ...state,
                schedule: [...defaultSchedule]
            }
        // возвращаем стандартное значение состояния
        default:
            return state;
    }
}

export default createStore(reducer);
