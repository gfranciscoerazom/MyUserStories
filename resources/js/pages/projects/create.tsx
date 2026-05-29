import { InputField } from '@/components/forms/input-field';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldGroup, FieldLegend, FieldSet } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import useCurrentTeam from '@/hooks/useCurrentTeam';
import { home } from '@/routes';
import projects from '@/routes/projects';
import { Form, Head, Link } from '@inertiajs/react';

export default function ProjectCreate() {
    const currentTeam = useCurrentTeam();

    return (
        <>
            <Head title="Create Project" />

            <h1 className="sr-only">Create Project</h1>

            <div className="space-y-6 p-4">
                <Form
                    {...projects.store.form(currentTeam.slug)}
                    resetOnSuccess
                    disableWhileProcessing
                    className="flex flex-col gap-6"
                >
                    {({ processing }) => (
                        <FieldGroup>
                            <FieldSet>
                                <FieldLegend>Create Project</FieldLegend>
                                <FieldDescription>Fill in the details below to create a new project.</FieldDescription>
                                <FieldGroup>
                                    <InputField
                                        name="name"
                                        type="text"
                                        label="Project Name"
                                        description="The name of your project."
                                        placeholder="My Awesome Project"
                                    />
                                    <InputField
                                        name="description"
                                        type="textarea"
                                        label="Project Description"
                                        description="A brief description of your project."
                                        placeholder="Describe your project in a few sentences."
                                    />
                                </FieldGroup>
                            </FieldSet>
                            <Field orientation="horizontal">
                                <Button type="submit" disabled={processing}>
                                    {processing && <Spinner />}
                                    Create Project
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

ProjectCreate.layout = (props: { currentTeam?: { slug: string; } | null; }) => ({
    breadcrumbs: [
        {
            title: 'Projects',
            href: props.currentTeam ? projects.index(props.currentTeam.slug) : home(),
        },
        {
            title: 'Create Project',
            href: props.currentTeam ? projects.create(props.currentTeam.slug) : home(),
        },
    ],
});


