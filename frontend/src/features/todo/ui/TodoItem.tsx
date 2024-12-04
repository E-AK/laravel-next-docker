import React from 'react';
import { Task } from '../model/types';

interface TodoItemProps {
    task: Task;
    onEdit: () => void;
    onDelete: () => void;
    onStatusChange: () => void;
}

export const TodoItem: React.FC<TodoItemProps> = ({ task, onEdit, onDelete, onStatusChange }) => {
    return (
        <li style={{ display: 'flex', justifyContent: 'space-between', padding: '10px', marginBottom: '10px', border: '1px solid #ddd', borderRadius: '5px' }}>
            <span>{task.text}</span>
            <span style={{ marginLeft: '10px', color: task.status === 'done' ? 'green' : task.status === 'does' ? 'orange' : 'gray' }}>
        {task.status.toUpperCase()}
      </span>
            <div style={{ display: 'flex', gap: '5px' }}>
                <button onClick={onStatusChange}>Next</button>
                <button onClick={onEdit}>Edit</button>
                <button onClick={onDelete}>Delete</button>
            </div>
        </li>
    );
};
