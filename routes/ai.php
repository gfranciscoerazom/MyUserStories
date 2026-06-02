<?php

declare(strict_types=1);

use App\Mcp\Servers\UserStoryServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::oauthRoutes();

Mcp::web('/mcp', UserStoryServer::class)->middleware('auth:api');
