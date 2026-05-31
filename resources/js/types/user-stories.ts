export type UserStory = {
    readonly id: number;
    readonly story: string;
    readonly status: 'todo' | 'in_progress' | 'done';
    readonly project_id: number;
    readonly user_id: number | null;
    readonly created_at: string;
    readonly updated_at: string;
};