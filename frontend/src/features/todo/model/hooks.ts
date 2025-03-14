import {useEffect, useState} from 'react';
import {
    createNotification,
    createTask,
    deleteNotification,
    deleteTask,
    fetchTasks,
    updateNotification,
    updateTask,
    updateTaskStatus
} from './api';
import {Notification, Task} from './types';

export const useTasks = () => {
    const [tasks, setTasks] = useState<Task[]>([]);

    useEffect(() => {
        fetchTasks().then(tasks => {
            setTasks(tasks)
        });
    }, []);

    const handleAddTask = async (text: string) => {
        const newTask = await createTask(text);
        setTasks((prev: Task[]) => [...prev, newTask]);
    };

    const editTask = (id: string, text: string) => {
        setTasks(prevTasks => {
            return prevTasks.map(task =>
                task.id === id ? {...task, text: text} : task
            );
        });
    };

    const handleEditTask = async (id: string, text: string) => {
        const updatedTask = await updateTask(id, text);
        setTasks((prev: Task[]) => prev.map((task) => (task.id === id ? updatedTask : task)));
    };

    const handleDeleteTask = async (id: string) => {
        await deleteTask(id);
        setTasks((prev: Task[]) => prev.filter((task) => task.id !== id));
    };

    const handleStatusChange = async (id: string) => {
        const updatedTask = await updateTaskStatus(id);
        setTasks((prev: Task[]) =>
            prev.map((task) => (task.id === id ? { ...task, status: updatedTask.status } : task))
        );
    };

    return { tasks, handleAddTask, editTask, handleEditTask, handleDeleteTask, handleStatusChange };
};

export const useNotifications = (taskId: string) => {
    const [notifications, setNotifications] = useState<Notification[]>([]);

    const addNotification = (notification: Notification) => {
        setNotifications((prev) => [...prev, notification]);
    };

    const handleAddNotification = async (datetime: string) => {
        const newNotification = await createNotification({ taskId, datetime });
        setNotifications((prev) => [...prev, newNotification]);
    };

    const handleEditNotification = async (id: string, datetime: string) => {
        const updatedNotification = await updateNotification(id, { taskId, datetime });
        setNotifications((prev) =>
            prev.map((notification) => (notification.id === id ? updatedNotification : notification))
        );
    };

    const handleDeleteNotification = async (id: string) => {
        await deleteNotification(id);
        setNotifications((prev) => prev.filter((notification) => notification.id !== id));
    };

    return { notifications, addNotification, handleAddNotification, handleEditNotification, handleDeleteNotification };
};