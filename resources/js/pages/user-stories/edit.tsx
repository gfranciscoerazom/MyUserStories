import { InputField } from '@/components/forms/input-field';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldGroup, FieldLegend, FieldSet } from '@/components/ui/field';
import { SelectItem } from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import useCurrentTeam from '@/hooks/useCurrentTeam';
import { home } from '@/routes';
import projects from '@/routes/projects';
import { Project, UserStory } from '@/types';
import { Form, Head, Link } from '@inertiajs/react';

export default function UserStoryEdit({ project, user_story }: { project: Project; user_story: UserStory; }) {
    const currentTeam = useCurrentTeam();

    return (
        <>
            <Head title={`Edit story — ${project.name}`} />

            <h1 className="sr-only">Edit user story</h1>

            <div className="space-y-6 p-4">
                {/* <Heading
                    variant="default"
                    title={`Edit story for ${project.name}`}
                    description="Modify the story text and status for this user story."
                /> */}

                <Form
                    {...projects.userStories.update.form({ current_team: currentTeam.slug, project: project.id, userStory: user_story.id })}
                    resetOnSuccess
                    disableWhileProcessing
                    className="flex flex-col gap-6"
                >
                    {({ processing }) => (
                        <FieldGroup>
                            <FieldSet>
                                <FieldLegend>Edit Story for {project.name}</FieldLegend>
                                <FieldDescription>
                                    Update the story text and select the current status.
                                </FieldDescription>
                                <FieldGroup>
                                    <InputField
                                        name="story"
                                        type="textarea"
                                        label="Story"
                                        description="Describe the user story in one or two sentences."
                                        placeholder="As a user, I want to..."
                                        defaultValue={user_story.story}
                                    />
                                    <InputField
                                        name="status"
                                        type="select"
                                        label="Status"
                                        description="Select the current progress status for this story."
                                        defaultValue={user_story.status}
                                        placeholder="Select status"
                                    >
                                        <SelectItem value="todo">Todo</SelectItem>
                                        <SelectItem value="in_progress">In progress</SelectItem>
                                        <SelectItem value="done">Done</SelectItem>
                                    </InputField>
                                </FieldGroup>
                            </FieldSet>
                            <Field orientation="horizontal">
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner className="mr-2" />}
                                    Save changes
                                </Button>
                                <Button variant="outline" asChild disabled={processing} type="button">
                                    <Link href={projects.userStories.show({ current_team: currentTeam.slug, project: project.id, userStory: user_story.id })} prefetch>
                                        Cancel
                                    </Link>
                                </Button>
                            </Field>
                        </FieldGroup>
                    )}
                </Form>
            </div>
        </>
    );
}

UserStoryEdit.layout = (props: { currentTeam?: { slug: string; } | null; project: Project; user_story: UserStory; }) => ({
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
            title: 'Edit story',
            href: props.currentTeam ? projects.userStories.edit({ current_team: props.currentTeam.slug, project: props.project.id, userStory: props.user_story.id }) : home(),
        },
    ],
});
