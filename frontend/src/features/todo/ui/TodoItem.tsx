import React, { useState } from 'react';
import { Task } from '../model/types';
import { TaskNotifications } from './TaskNotifications';
import {Input} from "@/shared/ui/Input";

interface TodoItemProps {
    task: Task;
    editTask: (id: string, text: string) => void;
    onEdit: (id: string, text: string) => void;
    onDelete: () => void;
    onStatusChange: () => void;
}

export const TodoItem: React.FC<TodoItemProps> = ({ task, editTask, onEdit, onDelete, onStatusChange }) => {
    const [isNotificationsOpen, setIsNotificationsOpen] = useState(false);

    const toggleNotifications = () => setIsNotificationsOpen(!isNotificationsOpen);

    return (
        <>
            <li style={{ display: 'flex', justifyContent: 'space-between', padding: '10px', marginBottom: '10px', border: '1px solid #ddd', borderRadius: '5px' }}>
                <Input
                    type="text"
                    value={task.text}
                    onChange={(value) => editTask(task.id, value)}
                    label=""
                />
                <span style={{ marginLeft: '10px', color: task.status === 'done' ? 'green' : task.status === 'does' ? 'orange' : 'gray' }}>
                    {task.status.toUpperCase()}
                </span>
                <div style={{ display: 'flex', gap: '5px' }}>
                    <button onClick={onStatusChange}>Next</button>
                    <button onClick={() => onEdit(task.id, task.text)}>Edit</button>
                    <button onClick={onDelete}>Delete</button>
                    <button onClick={toggleNotifications}>Notifications</button>
                </div>
            </li>
            {isNotificationsOpen && (
                <TaskNotifications
                    task={task}
                    onClose={toggleNotifications}
                />
            )}
        </>
    );
};
