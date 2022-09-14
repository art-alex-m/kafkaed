import './bootstrap';
import {updateSpeed} from "./speedchart";

Echo.channel('kafkaed-1-speed')
    .listen('Kafkaed1SpeedIsUpdated', (e) => {
        updateSpeed(e.speed);
    });
