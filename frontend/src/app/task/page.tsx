'use client';

import { useState, useEffect } from 'react';
import axios from 'axios';

export default function Todo() {
    const [tasks, setTasks] = useState([]);
    const [newTask, setNewTask] = useState('');
    const [newNotifications, setNewNotifications] = useState({});

    // Ensure the tasks are loaded only once
    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            axios({
                method: 'get',
                url: `http://localhost:8080/api/task/index`,
                headers: { 'Authorization': `Bearer ${token}` },
            }).then((response) => {
                setTasks(response.data.data);
            });
        }
    }, []);

    const createTask = (text) => {
        const token = localStorage.getItem('token');
        if (token) {
            axios({
                method: 'post',
                url: `http://localhost:8080/api/task/create`,
                data: { text },
                headers: { 'Authorization': `Bearer ${token}` },
            }).then((response) => {
                setTasks((prevTasks) => [
                    ...prevTasks,
                    {
                        id: response.data.data.id,
                        text: response.data.data.text,
                        status: response.data.data.status,
                        notifications: [],
                        isEditing: false,
                    },
                ]);
            });
        }
    };

    const editTask = (id, text) => {
        const token = localStorage.getItem('token');
        if (token) {
            axios({
                method: 'patch',
                url: `http://localhost:8080/api/task/edit/${id}`,
                data: { text },
                headers: { 'Authorization': `Bearer ${token}` },
            }).then((response) => {
                setTasks((prevTasks) =>
                    prevTasks.map((task) =>
                        task.id === id
                            ? { ...task, text: response.data.data.text, isEditing: false }
                            : task
                    )
                );
            });
        }
    };

    const toggleTaskStatus = (id) => {
        const token = localStorage.getItem('token');
        if (token) {
            axios({
                method: 'patch',
                url: `http://localhost:8080/api/task/status/${id}`,
                headers: { 'Authorization': `Bearer ${token}` },
            }).then((response) => {
                setTasks((prevTasks) =>
                    prevTasks.map((task) =>
                        task.id === id ? { ...task, status: response.data.data.status } : task
                    )
                );
            });
        }
    };

    const addNotification = (id, notification) => {
        const token = localStorage.getItem('token');
        if (token) {
            const formattedNotification = new Date(notification).toISOString().replace('T', ' ').split('.')[0];
            axios({
                method: 'post',
                url: `http://localhost:8080/api/task/${id}/notification`,
                data: { notification: formattedNotification },
                headers: { 'Authorization': `Bearer ${token}` },
            }).then((response) => {
                setTasks((prevTasks) =>
                    prevTasks.map((task) =>
                        task.id === id
                            ? { ...task, notifications: [...task.notifications, response.data.data.datetime] }
                            : task
                    )
                );
                setNewNotifications((prev) => ({ ...prev, [id]: '' }));
            });
        }
    };

    const deleteNotification = (taskId, notificationIndex) => {
        const token = localStorage.getItem('token');
        if (token) {
            axios({
                method: 'delete',
                url: `http://localhost:8080/api/task/${taskId}/notification/${notificationIndex}`,
                headers: { 'Authorization': `Bearer ${token}` },
            }).then(() => {
                setTasks((prevTasks) =>
                    prevTasks.map((task) =>
                        task.id === taskId
                            ? {
                                ...task,
                                notifications: task.notifications.filter((_, index) => index !== notificationIndex),
                            }
                            : task
                    )
                );
            });
        }
    };

    const handleAddTask = () => {
        if (newTask.trim() !== '') {
            createTask(newTask);
            setNewTask('');
        }
    };

    const handleAddNotification = (id) => {
        const notification = newNotifications[id];
        if (notification && notification.trim() !== '') {
            addNotification(id, notification);
        }
    };

    const handleEditToggle = (id) => {
        setTasks((prevTasks) =>
            prevTasks.map((task) => (task.id === id ? { ...task, isEditing: !task.isEditing } : task))
        );
    };

    return (
        <div style={{ maxWidth: '600px', margin: 'auto', padding: '20px' }}>
            <h1>TODO List</h1>
            <div style={{ display: 'flex', gap: '10px', marginBottom: '20px' }}>
                <input
                    type="text"
                    value={newTask}
                    onChange={(e) => setNewTask(e.target.value)}
                    placeholder="Enter a task"
                    style={{ flex: 1, padding: '10px', fontSize: '16px' }}
                />
                <button onClick={handleAddTask} style={{ padding: '10px' }}>
                    Add
                </button>
            </div>
            <ul style={{ listStyle: 'none', padding: 0 }}>
                {tasks.map((task) => (
                    <li
                        key={task.id}
                        style={{
                            padding: '10px',
                            marginBottom: '10px',
                            border: '1px solid #ddd',
                            borderRadius: '5px',
                        }}
                    >
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                            {task.isEditing ? (
                                <input
                                    type="text"
                                    defaultValue={task.text}
                                    onBlur={(e) => editTask(task.id, e.target.value)}
                                    autoFocus
                                    style={{ flex: 1, marginRight: '10px', padding: '5px' }}
                                />
                            ) : (
                                <span style={{ flex: 1 }}>{task.text}</span>
                            )}
                            <button onClick={() => toggleTaskStatus(task.id)}>Toggle Status</button>
                        </div>
                        <div style={{ marginTop: '10px' }}>
                            <strong>Notifications:</strong>
                            <ul>
                                {task.notifications?.map((notif, index) => (
                                    <li key={index} style={{ display: 'flex', justifyContent: 'space-between' }}>
                                        <span>{notif}</span>
                                        <button onClick={() => deleteNotification(task.id, index)}>Delete</button>
                                    </li>
                                ))}
                            </ul>
                            <div style={{ display: 'flex', gap: '10px', marginTop: '10px' }}>
                                <input
                                    type="datetime-local"
                                    value={newNotifications[task.id] || ''}
                                    onChange={(e) =>
                                        setNewNotifications((prev) => ({ ...prev, [task.id]: e.target.value }))
                                    }
                                    style={{ flex: 1 }}
                                />
                                <button onClick={() => handleAddNotification(task.id)}>Add Notification</button>
                            </div>
                        </div>
                        <div style={{ marginTop: '10px' }}>
                            <button onClick={() => handleEditToggle(task.id)}>
                                {task.isEditing ? 'Save' : 'Edit'}
                            </button>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}
