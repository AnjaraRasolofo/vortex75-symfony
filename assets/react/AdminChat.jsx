import React, { useEffect, useState } from 'react';
import axios from 'axios';
import '../styles/chat-admin.css';

export default function AdminChat() {

    const [conversations, setConversations] = useState([]);
    const [selected, setSelected] = useState(null);
    const [messages, setMessages] = useState([]);

    useEffect(() => {
        loadConversations();
    }, []);

    const loadConversations = async () => {
        const res = await axios.get('/admin/chat');
        setConversations(res.data);
    };

    const openConversation = async (id) => {
        setSelected(id);

        const res = await axios.get(`/admin/chat/${id}`);
        setMessages(res.data.messages);
    };

    const sendMessage = async (content) => {
        await axios.post(`/admin/chat/${selected}/send`, {
            content
        });

        openConversation(selected);
    };

    return (
        <div style={{ display: 'flex', height: '80vh' }}>

            {/* SIDEBAR */}
            <div style={{ width: '30%', borderRight: '1px solid #ddd' }}>
                <h3>Conversations</h3>

                {conversations.map(c => (
                    <div
                        key={c.id}
                        onClick={() => openConversation(c.id)}
                        style={{
                            padding: 10,
                            cursor: 'pointer',
                            background: selected === c.id ? '#eee' : '#fff'
                        }}
                    >
                        <strong>{c.customer}</strong>
                        <div>{c.lastMessage}</div>
                    </div>
                ))}
            </div>

            {/* CHAT */}
            <div className="admin-chat-container">
                {selected ? (
                    <>
                        <h3>Messages</h3>

                        <div className="admin-chat-box">
                            {messages.map(m => (
                                <div
                                    key={m.id}
                                    className={`admin-message ${m.isAdmin ? 'admin' : 'client'}`}
                                >
                                    <div className="admin-bubble">
                                        <span className="admin-sender">{m.sender}</span>
                                        <p>{m.content}</p>
                                    </div>
                                </div>
                            ))}
                        </div>

                        <ChatInput onSend={sendMessage} />
                    </>
                ) : (
                    <p>Sélectionne une conversation</p>
                )}
            </div>
        </div>
    );
}
/*
function ChatInput({ onSend }) {
    const [text, setText] = useState('');

    return (
        <div>
            <input
                value={text}
                onChange={e => setText(e.target.value)}
                placeholder="Répondre..."
            />
            <button onClick={() => {
                onSend(text);
                setText('');
            }}>
                Envoyer
            </button>
        </div>
    );
}*/

function ChatInput({ onSend }) {
    const [text, setText] = useState('');

    const handleSend = () => {
        if (!text.trim()) return;
        onSend(text);
        setText('');
    };

    return (
        <div className="chat-input-container">
            <input
                className="chat-input"
                value={text}
                onChange={e => setText(e.target.value)}
                placeholder="Répondre..."
                onKeyDown={(e) => e.key === 'Enter' && handleSend()}
            />
            <button className="chat-send-btn" onClick={handleSend}>
                Envoyer
            </button>
        </div>
    );
}