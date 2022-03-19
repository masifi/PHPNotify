<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WS Client</title>
    <style>
        body{
            background-color: #fafbfc;
            margin: 5em;
            font-family: Arial, Helvetica, sans-serif;
        }
        h1{
            text-align: center;
        }
        .cont{
            width: 90%;
            margin: 0 auto;
        }
        #msgboard{
            border-radius: 15px 15px 0 0;
            background-color: #fefefe;
            box-sizing: border-box;
            padding: 1em;
            border: 1px solid #ccc;
            height: 400px;
            overflow-y: auto;
            width: 100%;
        }
        #msg{
            font-size: 14pt;
            box-sizing: border-box;
            padding: 1em;
            border: 1px solid #ccc;
            height: 120px;
            overflow-y: auto;
            width: 100%;
            border-radius: 0 0 15px 15px;
            resize: none;
        }
        .msg{
            margin-right: auto;
            width: 60%;
            border: 1px solid #ccc;
            background: #eee;
            padding: 10px;
            border-radius: 25px;
        }
        .msg.mine{
            margin-right: 0;
            margin-left: auto;
            background: #eef;
        }
    </style>
</head>
<body>
    <div class="cont">
        <h1>Messenger</h1>
        <div id="msgboard"></div>
        <textarea id="msg" rows="6" placeholder="Enter to send"></textarea>
    </div>
    <script>
        var conn = new WebSocket('ws://localhost:4000');
        conn.onopen = function(e) {
            msgboard.innerHTML += "<p class='msg'><strong>onOpen:</strong> Connection established!</p>";
            console.log(e);
        };

        conn.onmessage = function(e) {
            console.log(e);
            var data = JSON.parse(e.data);
            var mine = '';
            if(data.mine) mine='mine';
            msgboard.innerHTML += `<p class='msg ${mine}'><strong>${data.from}:</strong> ${data.msg}</p>`;
            console.log(e.data);
        };

        conn.onclose = function(e) {
            console.log(e);
            msgboard.innerHTML = `<p class='msg'><strong>onClose:</strong> ${e.data}</p>`;
            console.log(e.data);
        };

        msg.onkeyup = function(e){
            if(e.keyCode == 13) {
                conn.send(msg.value);
                msg.value = '';
            }
        }
    </script>
</body>
</html>