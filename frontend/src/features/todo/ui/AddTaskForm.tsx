import React, { useState } from 'react';

interface AddTaskFormProps {
    onAddTask: (text: string) => void;
}

export const AddTaskForm: React.FC<AddTaskFormProps> = ({ onAddTask }) => {
    const [newTask, setNewTask] = useState('');

    const handleAdd = () => {
        if (newTask.trim()) {
            onAddTask(newTask);
            setNewTask('');
        }
    };

    return (
        <div style={{ display: 'flex', gap: '10px', marginBottom: '20px' }}>
            <input
                type="text"
                value={newTask}
                onChange={(e) => setNewTask(e.target.value)}
                placeholder="Enter a task"
                style={{ flex: 1, padding: '10px', fontSize: '16px' }}
            />
            <button onClick={handleAdd} style={{ padding: '10px' }}>
                Add
            </button>
        </div>
    );
};
