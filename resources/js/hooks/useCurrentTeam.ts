import { home } from '@/routes';
import type { Team } from '@/types/teams';
import { router, usePage } from '@inertiajs/react';

interface UseCurrentTeamOptions {
    redirectTo?: string;
    preserveState?: boolean;
}

export default function useCurrentTeam(
    options: UseCurrentTeamOptions = {},
): Team {
    const { currentTeam } = usePage().props;

    if (!currentTeam) {
        router.visit(options.redirectTo ?? home(), {
            preserveState: options.preserveState ?? true,
        });

        throw new Error('Current team not found. Redirecting to home.');
    }

    return currentTeam;
}
