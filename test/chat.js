document.getElementById('send').addEventListener('click', function() {
    var username = document.getElementById('username').value;
    var message = document.getElementById('message').value;

    fetch('chat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'username=' + encodeURIComponent(username) + '&message=' + encodeURIComponent(message),
    });

    document.getElementById('message').value = '';
});

setInterval(function() {
    fetch('chat.php')
        .then(response => response.json())
        .then(data => {
            var chat = document.getElementById('chat');
            chat.innerHTML = '';

            data.forEach(function(message) {
                var div = document.createElement('div');
                div.textContent = message.timestamp + ' ' + message.username + ': ' + message.message;
                chat.appendChild(div);
            });

            chat.scrollTop = chat.scrollHeight;
        });
}, 1000);