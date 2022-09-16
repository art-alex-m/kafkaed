(() => {
    let url = document.getElementById('control-speed').dataset.action;
    let controls = document.getElementsByClassName('transmission');

    let changeTransmission = async (e) => {
        let input = e.target;
        await fetch(url, {
            'method': 'put',
            'headers': {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            'body': JSON.stringify({
                'transmission': input.value
            })
        });
    };

    for (let i = 0; i < controls.length; i++) {
        let element = controls.item(i);
        element.addEventListener('click', changeTransmission);
    }
})();
