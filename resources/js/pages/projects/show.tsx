import { InputField } from '@/components/forms/input-field';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { Field, FieldDescription, FieldGroup, FieldLegend, FieldSet } from '@/components/ui/field';
import { Item, ItemActions, ItemContent, ItemGroup, ItemMedia, ItemTitle } from '@/components/ui/item';
import { Spinner } from '@/components/ui/spinner';
import useCurrentTeam from '@/hooks/useCurrentTeam';
import { home } from '@/routes';
import projects from '@/routes/projects';
import { Project, UserStory } from '@/types';
import { Form, Head, InfiniteScroll, Link } from '@inertiajs/react';
import { ChevronRight, CircleDashed, FolderCodeIcon, Trash } from 'lucide-react';

export default function ProjectShow({ project, user_stories }: { project: Project; user_stories: { data: UserStory[]; }; }) {
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

                <Form
                    {...projects.userStories.store.form({ current_team: currentTeam.slug, project: project.id })}
                    resetOnSuccess
                    disableWhileProcessing
                    className="mt-6 space-y-6"
                >
                    {({ processing }) => (
                        <FieldGroup>
                            <FieldSet>
                                <FieldLegend>Create User Story</FieldLegend>
                                <FieldDescription>
                                    Enter the user story details below.
                                </FieldDescription>
                                <FieldGroup>
                                    <InputField
                                        name="story"
                                        type="textarea"
                                        label="Story"
                                        description="Describe the user story in one or two sentences."
                                        placeholder="As a user, I want to..."
                                    />
                                </FieldGroup>
                            </FieldSet>
                            <Field orientation="horizontal">
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner className="mr-2" />}
                                    Add Story
                                </Button>
                            </Field>
                        </FieldGroup>
                    )}
                </Form>

                {
                    user_stories.data.length <= 0 ? (
                        <Empty>
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <FolderCodeIcon />
                                </EmptyMedia>
                                <EmptyTitle>No User Stories Yet</EmptyTitle>
                                <EmptyDescription>
                                    You haven&apos;t created any user stories yet. Get started by creating
                                    your first user story.
                                </EmptyDescription>
                            </EmptyHeader>
                        </Empty>
                    ) : (
                        <InfiniteScroll data="user_stories">
                            <ItemGroup className="gap-y-4">
                                {
                                    user_stories.data.map((story) => (
                                        <Item key={story.id} variant="outline" asChild>
                                            <Link href={projects.userStories.show({ current_team: currentTeam.slug, project: project.id, userStory: story.id })} prefetch>
                                                <ItemMedia variant="icon">
                                                    <CircleDashed />
                                                </ItemMedia>
                                                <ItemContent>
                                                    <ItemTitle>{story.story}</ItemTitle>
                                                    {/* <ItemDescription className="line-clamp-2">{story.story}</ItemDescription> */}
                                                </ItemContent>
                                                <ItemActions>
                                                    <ChevronRight className="size-5" />
                                                </ItemActions>
                                            </Link>
                                        </Item>
                                    ))
                                }
                            </ItemGroup>
                        </InfiniteScroll>
                    )
                }
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

