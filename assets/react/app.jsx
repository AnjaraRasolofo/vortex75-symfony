import React from 'react';
import { createRoot } from 'react-dom/client';
import ChatBox from './ChatBox';
import AdminChat from './AdminChat';
//// '../styles/chat.css';
//import '../styles/bootstrap.min.css';
//import '../styles/chat-admin.css';
import '../styles/app.css'

const container = document.getElementById('react-chat');

if (!container) {
    console.error('Le conteneur #react-chat est introuvable !');
} else {

    const root = createRoot(container);

    const type = container.dataset.type; // "chat" ou "admin"
    const conversationId = container.dataset.conversation;

    if (type === 'admin') {
        root.render(<AdminChat />);
    } else if(type === 'chat'){
        root.render(<ChatBox conversationId={conversationId} />);
    }
}