<?php
/**
 * Created by PhpStorm.
 * User: whis
 * Date: 6/30/17
 * Time: 10:09 AM
 */
?>
<input type="text" id="input" placeholder="Message…"/>
<hr/>
<pre id="output"></pre>

<script>
    var host = 'ws://127.0.0.1:8889';
    var socket = null;
    var input = document.getElementById('input');
    var output = document.getElementById('output');
    var print = function (message) {
        var samp = document.createElement('samp');
        samp.innerHTML = message + '\n';
        output.appendChild(samp);

        return false;
    };

    input.addEventListener('keyup', function (evt) {
        if (13 === evt.keyCode) {
            var msg = input.value;

            if (!msg) {
                return;
            }

            try {
                socket.send(msg);
                input.value = '';
                input.focus();
            } catch (e) {
                console.log(e);
            }

            return false;
        }
    });

    try {
        socket = new WebSocket(host);
        socket.onopen = function () {
            print('connection is opened');
            input.focus();

            return false;
        };
        socket.onmessage = function (msg) {
            print(msg.data);

            return false;
        };
        socket.onclose = function () {
            print('connection is closed');

            return false;
        };
    } catch (e) {
        console.log(e);
    }
</script>