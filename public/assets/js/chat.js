const chatToggle = document.getElementById('chat-toggle');
const chatClose = document.getElementById('chat-close');
const chatBox = document.getElementById('chat-box');
const chatMessages = document.getElementById('chat-messages');
const chatForm = document.getElementById('chat-form');
const chatInput = document.getElementById('chat-input');

let conversationId = null;
let eventSource = null;

function escapeHtml(text) {
    const div = document.createElement('div');
    div.innerText = text;
    return div.innerHTML;
}

function renderMessage(message) {
    const wrapper = document.createElement('div');
    wrapper.className = `chat-message ${message.mine ? 'mine' : 'other'}`;

    wrapper.innerHTML = `
        <div class="chat-bubble">
            <div>${escapeHtml(message.content)}</div>
            <small>${escapeHtml(message.createdAt ?? '')}</small>
        </div>
    `;

    chatMessages.appendChild(wrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function clearMessages() {
    chatMessages.innerHTML = '';
}

async function openConversation() {
    const response = await fetch(window.APP_CHAT.openUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    if (!response.ok) {
        throw new Error('Impossible d’ouvrir la conversation.');
    }

    const data = await response.json();
    conversationId = data.conversationId;

    return conversationId;
}

async function loadMessages() {
    const response = await fetch(`${window.APP_CHAT.messagesBaseUrl}/${conversationId}/messages`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    if (!response.ok) {
        throw new Error('Impossible de charger les messages.');
    }

    const messages = await response.json();

    clearMessages();
    messages.forEach(renderMessage);
}

async function sendMessage(content) {
    const response = await fetch(`${window.APP_CHAT.messagesBaseUrl}/${conversationId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ content })
    });

    if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.error || 'Erreur d’envoi.');
    }

    return await response.json();
}

function subscribeMercure() {
    if (!window.APP_CHAT.mercurePublicUrl || !conversationId) {
        return;
    }

    if (eventSource) {
        eventSource.close();
    }

    const url = new URL(window.APP_CHAT.mercurePublicUrl);
    url.searchParams.append('topic', `chat/conversation/${conversationId}`);

    eventSource = new EventSource(url.toString());

    eventSource.onmessage = (event) => {
        const data = JSON.parse(event.data);

        const alreadyExists = [...chatMessages.querySelectorAll('.chat-bubble')]
            .some(el => el.dataset.messageId === String(data.id));

        const wrapper = document.createElement('div');
        wrapper.className = `chat-message`;
        wrapper.innerHTML = `
            <div class="chat-bubble">
                <div>${escapeHtml(data.content)}</div>
                <small>${escapeHtml(data.createdAt ?? '')}</small>
            </div>
        `;

        wrapper.querySelector('.chat-bubble').dataset.messageId = data.id;

        if (!alreadyExists) {
            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    };
}

async function initChat() {
    if (!conversationId) {
        await openConversation();
        await loadMessages();
        subscribeMercure();
    }
}

if (chatToggle) {
    chatToggle.addEventListener('click', async () => {
        chatBox.classList.remove('chat-hidden');
        await initChat();
    });
}

if (chatClose) {
    chatClose.addEventListener('click', () => {
        chatBox.classList.add('chat-hidden');
    });
}

if (chatForm) {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const content = chatInput.value.trim();
        if (!content) return;

        try {
            await initChat();
            await sendMessage(content);
            chatInput.value = '';
        } catch (error) {
            alert(error.message);
        }
    });
}