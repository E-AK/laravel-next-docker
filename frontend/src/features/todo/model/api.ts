import axios from '@/shared/lib/axiosInstance';
import { Task } from './types';

export const fetchTasks = async (): Promise<Task[]> => {
    const response = await axios.get('/tasks/index');
    return response.data.data;
};

export const createTask = async (text: string): Promise<Task> => {
    const response = await axios.post('/tasks/create', { text });
    return response.data.data;
};

export const updateTask = async (id: string, text: string): Promise<Task> => {
    const response = await axios.patch(`/tasks/edit/${id}`, { text });
    return response.data.data;
};

export const deleteTask = async (id: string): Promise<void> => {
    await axios.delete(`/tasks/delete/${id}`);
};

export const updateTaskStatus = async (id: string): Promise<Task> => {
    const response = await axios.patch(`/tasks/status/${id}`);
    return response.data.data;
};

export const getNotificationsByTaskId = async (taskId: string): Promise<Notification[]> => {
    const response = await axios.get(`/notifications/task/${taskId}`);
    return response.data.data;
};

export const createNotification = async (data: object): Promise<Notification> => {
    const response = await axios.post('/notifications', data);
    return response.data.data;
};

export const deleteNotification = async (id: string): Promise<void> => {
    await axios.delete(`/task/notifications/${id}`);
};

export const updateNotification = async (id: string, data: object): Promise<Notification> => {
    const response = await axios.put(`/task/notifications/${id}`, data);
    return response.data.data;
};
