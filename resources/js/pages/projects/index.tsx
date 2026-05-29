import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemMedia, ItemTitle } from '@/components/ui/item';
import useCurrentTeam from '@/hooks/useCurrentTeam';
import { home } from '@/routes';
import projects from '@/routes/projects';
import { Project } from '@/types';
import { Head, InfiniteScroll, Link } from '@inertiajs/react';
import { ChevronRight, CircleDashed, FolderCodeIcon, Plus } from 'lucide-react';

export default function ProjectIndex({ team_projects }: { team_projects: { data: Project[]; }; }) {
    const currentTeam = useCurrentTeam();

    return (
        <>
            <Head title="Projects" />

            <h1 className="sr-only">Projects List</h1>

            <div className="space-y-6 p-4">
                <div className="flex items-center justify-end">
                    <Button asChild>
                        <Link href={projects.create(currentTeam.slug)} prefetch>
                            <Plus />
                            New Project
                        </Link>
                    </Button>
                </div>
                <Heading
                    variant="small"
                    title="Projects"
                    description="Manage your projects."
                />
                {
                    team_projects.data.length <= 0 ? (
                        <Empty>
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <FolderCodeIcon />
                                </EmptyMedia>
                                <EmptyTitle>No Projects Yet</EmptyTitle>
                                <EmptyDescription>
                                    You haven&apos;t created any projects yet. Get started by creating
                                    your first project.
                                </EmptyDescription>
                            </EmptyHeader>
                            <EmptyContent className="flex-row justify-center gap-2">
                                <Button asChild>
                                    <Link href={projects.create(currentTeam.slug)} prefetch>
                                        Create Project
                                    </Link>
                                </Button>
                            </EmptyContent>
                        </Empty>
                    ) : (
                        <InfiniteScroll data="team_projects">
                            <ItemGroup className="gap-y-4">
                                {
                                    team_projects.data.map((project) => (
                                        <Item key={project.id} variant="outline" asChild>
                                            <Link href={projects.show({ current_team: currentTeam.slug, project: project.id })} prefetch>
                                                <ItemMedia variant="icon">
                                                    <CircleDashed />
                                                </ItemMedia>
                                                <ItemContent>
                                                    <ItemTitle>{project.name}</ItemTitle>
                                                    <ItemDescription className="line-clamp-2">{project.description}</ItemDescription>
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

ProjectIndex.layout = (props: { currentTeam?: { slug: string; } | null; }) => ({
    breadcrumbs: [
        {
            title: 'Projects',
            href: props.currentTeam ? projects.index(props.currentTeam.slug) : home(),
        },
    ],
});

