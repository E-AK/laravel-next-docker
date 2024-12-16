import React, { useState, useEffect } from 'react';
import { useNotifications } from '../model/hooks';
import {Task} from "@/features/todo/model/types";
import moment from "moment";

interface TaskNotificationsProps {
    task: Task;
    onClose: () => void;
}

const formatDatetime = (datetime: string): string => {
    const date = new Date(datetime);
    return date.toUTCString();
};

export const TaskNotifications: React.FC<TaskNotificationsProps> = ({ task, onClose }) => {
    const { notifications, addNotification, handleAddNotification, handleEditNotification, handleDeleteNotification } = useNotifications(task.id);
    const [newDatetime, setNewDatetime] = useState<string>('');

    let loaded = false;

    useEffect(() => {
        if (loaded) {
            return;
        }

        loaded = true;

        task.notifications?.forEach(notification => {
            addNotification(notification)
        })
    }, []);

    const handleAdd = async () => {
        if (!newDatetime) return;
        const formattedDatetime = formatDatetime(newDatetime);
        await handleAddNotification(formattedDatetime);
        setNewDatetime('');
    };

    const handleUpdate = async (id: string, datetime: string) => {
        const formattedDatetime = formatDatetime(datetime);
        await handleEditNotification(id, formattedDatetime);
    };

    return (
        <div
            style={{
                position: 'absolute',
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                backgroundColor: 'rgba(0, 0, 0, 0.5)',
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
            }}
        >
            <div
                style={{
                    backgroundColor: '#fff',
                    padding: '20px',
                    borderRadius: '5px',
                    width: '400px',
                }}
            >
                <h3>Notifications for Task</h3>
                <ul>
                    {notifications.map((notif) => (
                        <li
                            key={notif.id}
                            style={{
                                display: 'flex',
                                justifyContent: 'space-between',
                                marginBottom: '10px',
                            }}
                        >
                            <input
                                type="datetime-local"
                                value={moment(notif.datetime).local().format('YYYY-MM-DD HH:mm:ss')}
                                onChange={(e) => handleUpdate(notif.id, e.target.value)}
                                style={{ marginRight: '10px', flex: 1 }}
                            />
                            <button onClick={() => handleDeleteNotification(notif.id)}>Delete</button>
                        </li>
                    ))}
                </ul>
                <div style={{ display: 'flex', marginTop: '10px', gap: '10px' }}>
                    <input
                        type="datetime-local"
                        value={newDatetime}
                        onChange={(e) => setNewDatetime(e.target.value)}
                        style={{ flex: 1 }}
                    />
                    <button onClick={handleAdd}>Add</button>
                </div>
                <button onClick={onClose} style={{ marginTop: '10px' }}>
                    Close
                </button>
            </div>
        </div>
    );
};