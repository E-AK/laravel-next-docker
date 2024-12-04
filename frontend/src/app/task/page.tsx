'use client';

import { TodoList } from '@/features/todo/ui/TodoList';
import { AddTaskForm } from '@/features/todo/ui/AddTaskForm';
import { useTasks } from '@/features/todo/model/hooks';

export default function TodoPage() {
    const { tasks, handleAddTask, handleDeleteTask, handleEditTask, handleStatusChange } = useTasks();

    return (
        <div style={{ maxWidth: '600px', margin: 'auto', padding: '20px' }}>
            <h1>TODO List</h1>
            <AddTaskForm onAddTask={handleAddTask} />
            <TodoList
                tasks={tasks}
                onEditTask={handleEditTask}
                onDeleteTask={handleDeleteTask}
                onStatusChange={handleStatusChange}
            />
        </div>
    );
}