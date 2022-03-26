<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WS Pusher/Client</title>
    <style>
        *{box-sizing: border-box;}
        body{ background-color: #fafbfc; margin: 5em; font-family: Arial, Helvetica, sans-serif; }
        h1{ text-align: center; }
        .cont{ width: 90%; margin: 0 auto; }
        #msgboard{ border-radius: 15px; background-color: #fefefe; box-sizing: border-box; padding: 1em; border: 1px solid #ccc; height: 300px; overflow-y: auto; width: 100%; }
        #msg{ font-size: 14pt; box-sizing: border-box; padding: 1em; border: 1px solid #ccc; height: 100px; overflow-y: auto; width: 100%; border-radius: 0 0 15px 15px; resize: none; }
        button{ font-size: 14pt; box-sizing: border-box; padding: .5em; border: 1px solid #ccc; background-color: #fafbff; height: 60px; overflow-y: auto; width: 100%; border-radius: 0 0 15px 15px; resize: none; }
        button:hover{ background-color: #eef; }
        textarea{ font-size: 14pt; box-sizing: border-box; padding: 1em; border: 1px solid #ccc; height: 100px; overflow-y: auto; width: 100%; border-radius: 0; resize: none; }
        select,input{ font-size: 14pt; box-sizing: border-box; padding: 1em; margin-bottom: 3px; border: 1px solid #ccc; height: 45px; overflow-y: auto; width: 50%; resize: none; }
        .title{border-radius: 15px 0 0 0; margin-right: 1%;}
        .category{border-radius: 0 15px 0 0;}
        .msg{ margin-right: auto; margin-bottom: 5px; width: 100%; border: 1px solid #ccc; background: #eee; padding: 10px; border-radius: 25px;}
        .msg.mine{ margin-right: 0; margin-left: auto; background: #eef; }
        .no-margin{margin: 0;}
        .row{display: flex; flex-grow: 1 1;}
    </style>
</head>
<body>
    <div class="cont">
        <h1>Blog Post</h1>
        <form id="blog_form" action="process.php" method="post">
            <div class="row">
                <input type="text" class="title" name="title"placeholder="Post Title" value="Blog post test title">
                <select class="category" name="category">
                    <option value="">Select Category</option>
                    <option selected value="kitten-cat">Kitten Category (kitten-cat)</option>
                </select>
            </div>
            <textarea name="body" rows="3" placeholder="Post Body">Blog post test body</textarea>
            <button>Save</button>
        </form>

        <h1>Notification</h1>
        <div id="msgboard"></div>
        <!-- <textarea id="msg" rows="4" placeholder="Enter to send"></textarea> -->
    </div>
    <script src="asset/autobahn.js"></script>
    <script>
        var conn = new ab.Session('ws://localhost:8080',
            function() {
                conn.subscribe('kitten-cat', function(topic, data) {
                    msgboard.innerHTML +=  msg(data.title+':',data.body,'h4');
                    console.log('New article published to category "' + topic + '" : ' + data.title);
                });
            },
            function(e) {
                msgboard.innerHTML = msg('onClose:',e.data);
                console.warn('WebSocket connection closed');
            },
            { 'skipSubprotocolCheck': true }
        );

        blog_form.onsubmit = (e) => {
            e.preventDefault();

            let data = new FormData(blog_form);
            const formData = Object.fromEntries(data.entries());

            fetch('./process.php',{
                method: 'POST',
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(res => { /*msgboard.innerHTML += msg('Result:', res.msg);*/ })
            .catch( err => console.log(err));
        }
        
        function msg(title, body, tag = 'strong'){
            return `<div class='msg'><${tag} class='no-margin'>${title}</${tag}> <p class="no-margin">${body}</p></div>`;
        }
    </script>
</body>
</html>