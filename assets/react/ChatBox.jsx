// assets/react/ChatBox.jsx
import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import '../styles/chat.css';

export default function ChatBox() {
    const [open, setOpen] = useState(false);
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState('');
    const [conversationId, setConversationId] = useState(null);
    const [jwtToken, setJwtToken] = useState(null);

    const eventSourceRef = useRef(null);

    const openConversation = async () => {
        setOpen(true);
        const res = await axios.get('/chat/open');
        setConversationId(res.data.conversationId);
        setJwtToken(res.data.jwtToken);
        //console.log('Token:', jwtToken);
        await loadMessages(res.data.conversationId);
    }

    const loadMessages = async(convId) => {
        const res = await axios.get(`/chat/${convId}/messages`);
        setMessages(res.data);
    }

    const sendMessage = async () => {
        if (!input.trim() || !conversationId) return;

        const content = input;

        setMessages(prev => [...prev, { text: content, fromUser: true }]);
        setInput('');

        await axios.post(`/chat/${conversationId}/send`, {
            content: content
        });
    };

    useEffect(() => {
        if (!conversationId || !jwtToken) return;

        const es = new EventSource(
            `http://localhost:3000/.well-known/mercure?topic=chat/conversation/${conversationId}&jwt=${jwtToken}`
        );

        es.onmessage = (event) => {
            const data = JSON.parse(event.data);
            setMessages(prev => [...prev, { text: data.content, fromUser: false }]);
        };

        return () => es.close();
    }, [conversationId, jwtToken]);

    return (
        <>
            {/* Bouton flottant */}
            <button onClick={openConversation} className="chat-fab">
                💬
            </button>

            {/* Chatbox */}
            <div className={`chat-box ${open ? 'chat-visible' : ''}`}>
                
                {/* Header */}
                <div className="chat-header">
                    <div>
                        Support
                        <div className="chat-subtitle">En ligne</div>
                    </div>
                    <button
                        className="chat-close"
                        onClick={() => setOpen(false)}
                    >
                        ×
                    </button>
                </div>

                {/* Messages */}
                <div className="chat-messages">
                    {messages.map((msg, i) => (
                        <div
                            key={i}
                            className={`chat-message ${msg.isAdmin ? 'other' : 'mine'}`}
                        >
                            <div className="chat-bubble">
                                {msg.text}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Input */}
                <div className="chat-form">
                    <input
                        className="chat-input"
                        value={input}
                        onChange={(e) => setInput(e.target.value)}
                        onKeyDown={(e) => e.key === 'Enter' && sendMessage()}
                        placeholder="Écrire un message..."
                    />
                    <button className="chat-send" onClick={sendMessage}>
                        Envoyer
                    </button>
                </div>
            </div>
        </>
    );
}