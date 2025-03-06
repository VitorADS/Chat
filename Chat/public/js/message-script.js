window.addEventListener('DOMContentLoaded', (e) => {
    const ws = new WebSocket('ws://11.10.0.11:9501');
    const chatBox = document.getElementById('chat-box');
    const messageContainer = chatBox.querySelector('.message-container');

    document.getElementById('send-button').addEventListener('click', function () {
        const messageInput = document.getElementById('message-input');
        const messageText = messageInput.value.trim();

        if (messageText !== '') {
            const newMessage = document.createElement('div');
            newMessage.classList.add('message', 'sent');
            newMessage.innerHTML = `
                    <p>${messageText}</p>
                    <span class="message-time">${new Date().toLocaleTimeString()}</span>
                `;

            messageContainer.appendChild(newMessage);
            messageInput.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            ws.send(JSON.stringify({
                'message': messageText
            }));
        }
    });

    document.getElementById('message-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            document.getElementById('send-button').click();
        }
    });

    ws.addEventListener('message', message => {
        const dados = JSON.parse(message.data);
        if (dados.type === 'chat') {
            const json = JSON.parse(dados.text);
            const value = json.message;

            const newMessage = document.createElement('div');
            newMessage.classList.add('message', 'received');
            newMessage.innerHTML = `
                    <p>${value}</p>
                    <span class="message-time">${new Date().toLocaleTimeString()}</span>
                `;

            messageContainer.appendChild(newMessage);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
});