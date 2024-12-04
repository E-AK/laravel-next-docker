export type TaskStatus = 'todo' | 'does' | 'done';

export interface Task {
    id: string;
    text: string;
    status: TaskStatus;
    isEditing?: boolean;
}

export interface Notification {
    id?: string;
    taskId: string;
    time: string;
}