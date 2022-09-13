import './bootstrap';

Echo.channel('kafkaed-1-speed')
    .listen('Kafkaed1SpeedIsUpdated', (e) => {
        console.log(e.speed);
    });
