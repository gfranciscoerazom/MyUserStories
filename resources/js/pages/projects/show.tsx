import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import useCurrentTeam from '@/hooks/useCurrentTeam';
import { home } from '@/routes';
import projects from '@/routes/projects';
import { Project } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Trash } from 'lucide-react';

export default function ProjectShow({ project }: { project: Project; }) {
    const currentTeam = useCurrentTeam();

    return (
        <>
            <Head title={project.name} />

            <h1 className="sr-only">{project.name} project</h1>

            <div className="space-y-6 p-4">
                <div className="flex items-center justify-end gap-4">
                    <Button asChild>
                        <Link href={projects.edit({ current_team: currentTeam.slug, project: project.id })} prefetch>
                            Edit Project
                        </Link>
                    </Button>
                    <Dialog>
                        <DialogTrigger asChild>
                            <Button variant="destructive">
                                <Trash />
                                Delete Project
                            </Button>
                        </DialogTrigger>
                        <DialogContent showCloseButton={false}>
                            <DialogHeader>
                                <DialogTitle>
                                    ¿Do you want to delete this project?
                                </DialogTitle>
                                <DialogDescription>
                                    This action cannot be undone. This will
                                    permanently delete the project.
                                </DialogDescription>
                            </DialogHeader>
                            <DialogFooter>
                                <Button variant="destructive" asChild>
                                    <Link
                                        href={projects.destroy({
                                            current_team: currentTeam.slug,
                                            project: project.id,
                                        })}
                                    >
                                        <Trash />
                                        Delete
                                    </Link>
                                </Button>
                                <DialogClose asChild>
                                    <Button variant="outline">Cancel</Button>
                                </DialogClose>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>
                </div>
                <Heading
                    variant="default"
                    title={project.name}
                    description={project.description}
                />
            </div>
        </>
    );
}

ProjectShow.layout = (props: { currentTeam?: { slug: string; } | null; project: Project; }) => ({
    breadcrumbs: [
        {
            title: 'Projects',
            href: props.currentTeam ? projects.index(props.currentTeam.slug) : home(),
        },
        {
            title: props.project.name,
            href: props.currentTeam ? projects.show({ current_team: props.currentTeam.slug, project: props.project.id }) : home(),
        },
    ],
});

