'use client';

import { useState, useEffect } from 'react';
import axios from 'axios';

export default function Todo() {
    const [tasks, setTasks] = useState([]);
    const [newTask, setNewTask] = useState('');

    let loaded = false;

    useEffect(() => {
        if (loaded) {
            return;
        }

        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'get',
                url: `http://localhost:8080/api/task/index`,
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    setTasks(response.data.data);
                })
        }

        loaded = true;
    }, []);

    const createTask = (text) => {
        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'post',
                url: `http://localhost:8080/api/task/create`,
                data: {
                    'text': text
                },
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    setTasks([
                        ...tasks,
                        { id: response.data.data.id, text: response.data.data.text, status: response.data.data.status, isEditing: false },
                    ]);
                })
        }
    }

    const edit = (id, text) => {
        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'patch',
                url: `http://localhost:8080/api/task/edit/${id}`,
                data: {
                    'text': text
                },
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    setTasks(
                        tasks.map((task) =>
                            task.id === id ? { ...task, text: response.data.data.text, isEditing: false } : task
                        )
                    );
                })
        }
    }

    const deleteTask = (id) => {
        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'delete',
                url: `http://localhost:8080/api/task/delete/${id}`,
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    setTasks(tasks.filter((task) => task.id !== id));
                })
        }
    }

    const todo = (id) => {
        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'patch',
                url: `http://localhost:8080/api/task/todo/${id}`,
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    setTasks(
                        tasks.map((task) => {
                            return task.id === id ? { ...task, status: response.data.data.status } : task;
                        })
                    );
                })
        }
    }

    const does = (id) => {
        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'patch',
                url: `http://localhost:8080/api/task/does/${id}`,
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    setTasks(
                        tasks.map((task) => {
                            return task.id === id ? { ...task, status: response.data.data.status } : task;
                        })
                    );
                })
        }
    }

    const done = (id) => {
        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'patch',
                url: `http://localhost:8080/api/task/done/${id}`,
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    setTasks(
                        tasks.map((task) => {
                            return task.id === id ? { ...task, status: response.data.data.status } : task;
                        })
                    );
                })
        }
    }

    const handleAddTask = () => {
        if (newTask.trim() === '') return;
        createTask(newTask);
        setNewTask('');
    };

    const handleEditTask = (id) => {
        setTasks(
            tasks.map((task) =>
                task.id === id ? { ...task, isEditing: !task.isEditing } : task
            )
        );
    };

    const handleSaveTask = (id, newText) => {
        edit(id, newText);
    };

    const handleDeleteTask = (id) => {
        deleteTask(id);
    };

    const handleStatusChange = (id) => {
        const statuses = ['todo', 'does', 'done'];

        let task = tasks.find(t => t.id === id);

        const currentIndex = statuses.indexOf(task.status);
        const nextStatus = statuses[(currentIndex + 1) % statuses.length];

        if (nextStatus === 'todo') {
            todo(id);
        }
        else if (nextStatus === 'does') {
            does(id);
        }
        else if (nextStatus === 'done') {
            done(id);
        }
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
                            display: 'flex',
                            justifyContent: 'space-between',
                            alignItems: 'center',
                            padding: '10px',
                            marginBottom: '10px',
                            border: '1px solid #ddd',
                            borderRadius: '5px',
                        }}
                    >
                        {task.isEditing ? (
                            <input
                                type="text"
                                defaultValue={task.text}
                                onBlur={(e) => handleSaveTask(task.id, e.target.value)}
                                autoFocus
                                style={{ flex: 1, marginRight: '10px', padding: '5px' }}
                            />
                        ) : (
                            <span style={{ flex: 1 }}>{task.text}</span>
                        )}
                        <span
                            style={{
                                marginLeft: '10px',
                                fontSize: '14px',
                                color: task.status === 'done' ? 'green' : task.status === 'does' ? 'orange' : 'gray',
                            }}
                        >
                            {task.status.toUpperCase()}
                        </span>
                        <div style={{ display: 'flex', gap: '5px', marginLeft: '10px' }}>
                            <button onClick={() => handleStatusChange(task.id)}>Next</button>
                            <button onClick={() => handleEditTask(task.id)}>
                                {task.isEditing ? 'Save' : 'Edit'}
                            </button>
                            <button onClick={() => handleDeleteTask(task.id)}>Delete</button>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    )
}