import { InputField } from '@/components/forms/input-field';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldGroup, FieldLegend, FieldSet } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import useCurrentTeam from '@/hooks/useCurrentTeam';
import { home } from '@/routes';
import projects from '@/routes/projects';
import { Project } from '@/types';
import { Form, Head, Link } from '@inertiajs/react';

export default function ProjectEdit({ project }: { project: Project; }) {
    const currentTeam = useCurrentTeam();

    return (
        <>
            <Head title={`Edit ${project.name}`} />

            <h1 className="sr-only">Edit {project.name}</h1>

            <div className="space-y-6 p-4">
                <Form
                    {...projects.update.form({ current_team: currentTeam.slug, project: project.id })}
                    resetOnSuccess
                    disableWhileProcessing
                    className="flex flex-col gap-6"
                >
                    {({ processing }) => (
                        <FieldGroup>
                            <FieldSet>
                                <FieldLegend>Edit Project</FieldLegend>
                                <FieldDescription>Update the details below to modify your project.</FieldDescription>
                                <FieldGroup>
                                    <InputField
                                        name="name"
                                        type="text"
                                        label="Project Name"
                                        description="The name of your project."
                                        placeholder="My Awesome Project"
                                        defaultValue={project.name}
                                    />
                                    <InputField
                                        name="description"
                                        type="textarea"
                                        label="Project Description"
                                        description="A brief description of your project."
                                        placeholder="Describe your project in a few sentences."
                                        defaultValue={project.description}
                                    />
                                </FieldGroup>
                            </FieldSet>
                            <Field orientation="horizontal">
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner />}
                                    Update Project
                                </Button>
                                <Button variant="outline" asChild disabled={processing} type="button">
                                    <Link href={projects.index(currentTeam.slug)} prefetch>
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

ProjectEdit.layout = (props: { currentTeam?: { slug: string; } | null; project: Project; }) => ({
    breadcrumbs: [
        {
            title: 'Projects',
            href: props.currentTeam ? projects.index(props.currentTeam.slug) : home(),
        },
        {
            title: 'Edit Project',
            href: props.currentTeam ? projects.edit({ current_team: props.currentTeam.slug, project: props.project.id }) : home(),
        },
    ],
});



