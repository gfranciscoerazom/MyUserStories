import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import useCurrentTeam from '@/hooks/useCurrentTeam';
import { home } from '@/routes';
import projects from '@/routes/projects';
import { Project, UserStory } from '@/types';
import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft, Pencil, Trash } from 'lucide-react';

export default function UserStoryShow({ project, user_storie }: { project: Project; user_storie: UserStory; }) {
    const currentTeam = useCurrentTeam();

    return (
        <>
            <Head title={user_storie.story.substring(0, 50)} />

            <h1 className="sr-only">User story</h1>

            <div className="space-y-6 p-4">
                <div className="flex gap-4 flex-row items-center justify-between">
                    <Button variant="secondary" asChild>
                        <Link href={projects.show({ current_team: currentTeam.slug, project: project.id })} prefetch>
                            <ArrowLeft />
                            Back to project
                        </Link>
                    </Button>
                    <div className="flex items-center gap-4">
                        <Button asChild>
                            <Link href={projects.userStories.edit({ current_team: currentTeam.slug, project: project.id, userStorie: user_storie.id })} prefetch>
                                <Pencil />
                                Edit story
                            </Link>
                        </Button>
                        <Dialog>
                            <DialogTrigger asChild>
                                <Button variant="destructive">
                                    <Trash />
                                    Delete
                                </Button>
                            </DialogTrigger>
                            <DialogContent showCloseButton={false}>
                                <DialogHeader>
                                    <DialogTitle>Delete user story?</DialogTitle>
                                    <DialogDescription>
                                        This action cannot be undone. The user story will be removed from the project.
                                    </DialogDescription>
                                </DialogHeader>
                                <Form {...projects.userStories.destroy.form({ current_team: currentTeam.slug, project: project.id, userStorie: user_storie.id })}>
                                    <DialogFooter>
                                        <Button variant="destructive" type="submit">
                                            Delete
                                        </Button>
                                        <DialogClose asChild>
                                            <Button variant="outline">Cancel</Button>
                                        </DialogClose>
                                    </DialogFooter>
                                </Form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                <Heading
                    variant="default"
                    title="User Story"
                    description={user_storie.story}
                />
            </div>
        </>
    );
}

UserStoryShow.layout = (props: { currentTeam?: { slug: string; } | null; project: Project; user_storie: UserStory; }) => ({
    breadcrumbs: [
        {
            title: 'Projects',
            href: props.currentTeam ? projects.index(props.currentTeam.slug) : home(),
        },
        {
            title: props.project.name,
            href: props.currentTeam ? projects.show({ current_team: props.currentTeam.slug, project: props.project.id }) : home(),
        },
        {
            title: 'User story',
            href: props.currentTeam ? projects.userStories.show({ current_team: props.currentTeam.slug, project: props.project.id, userStorie: props.user_storie.id }) : home(),
        },
    ],
});
