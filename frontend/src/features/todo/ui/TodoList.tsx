import React from 'react';
import { TodoItem } from './TodoItem';
import { Task } from '../model/types';

interface TodoListProps {
    tasks: Task[];
    onEditTask: (id: string) => (id: string, text: string) => Promise<void>;
    onDeleteTask: (id: string) => void;
    onStatusChange: (id: string) => void;
}

export const TodoList: React.FC<TodoListProps> = ({ tasks, onEditTask, onDeleteTask, onStatusChange }) => {
    return (
        <ul style={{ listStyle: 'none', padding: 0 }}>
            {tasks?.map((task) => (
                <TodoItem
                    key={task.id}
                    task={task}
                    onEdit={() => onEditTask(task.id)}
                    onDelete={() => onDeleteTask(task.id)}
                    onStatusChange={() => onStatusChange(task.id)}
                />
            ))}
        </ul>
    );
};
