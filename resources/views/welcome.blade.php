<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --swagger-bg: #1b1b1b;
            --swagger-header: #0d1117;
            --swagger-text: #ffffff;
            --swagger-primary: #4990e2;
            --swagger-secondary: #3a4151;
            --swagger-accent: #49cc90;
            --swagger-danger: #f93e3e;
            --swagger-warning: #fca130;
        }
        
        body {
            background-color: var(--swagger-bg);
            color: var(--swagger-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        
        .swagger-header {
            background-color: var(--swagger-header);
            padding: 20px 0;
            border-bottom: 1px solid var(--swagger-secondary);
        }
        
        .api-title {
            color: var(--swagger-text);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .api-version {
            background-color: var(--swagger-primary);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .server-badge {
            background-color: var(--swagger-secondary);
            color: var(--swagger-text);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-right: 10px;
        }
        
        .endpoint-card {
            background-color: var(--swagger-secondary);
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid #3a4151;
        }
        
        .endpoint-header {
            padding: 15px 20px;
            background-color: #2d3341;
            border-bottom: 1px solid #3a4151;
        }
        
        .method-badge {
            padding: 5px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-right: 10px;
        }
        
        .method-get {
            background-color: var(--swagger-accent);
            color: #000;
        }
        
        .method-post {
            background-color: var(--swagger-primary);
            color: white;
        }
        
        .endpoint-path {
            font-family: monospace;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .endpoint-summary {
            margin-left: 10px;
            color: #d0d0d0;
        }
        
        .endpoint-body {
            padding: 20px;
        }
        
        .tag-badge {
            background-color: #3a4151;
            color: #8b9dc3;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-right: 8px;
        }
        
        .response-badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .response-200 {
            background-color: var(--swagger-accent);
            color: #000;
        }
        
        .response-400, .response-404, .response-422, .response-500 {
            background-color: var(--swagger-danger);
            color: white;
        }
        
        .schema-box {
            background-color: #1b1b1b;
            border: 1px solid #3a4151;
            border-radius: 4px;
            padding: 15px;
            margin-top: 15px;
            font-family: monospace;
            font-size: 0.9rem;
        }
        
        .schema-property {
            margin-left: 20px;
        }
        
        .schema-type {
            color: #49cc90;
        }
        
        .schema-key {
            color: #ffffff;
        }
        
        .schema-description {
            color: #a0a0a0;
            font-style: italic;
        }
        
        .example-box {
            background-color: #2d3341;
            border-left: 4px solid var(--swagger-primary);
            padding: 15px;
            margin-top: 15px;
            font-family: monospace;
            font-size: 0.9rem;
        }
        
        .section-title {
            border-bottom: 1px solid var(--swagger-secondary);
            padding-bottom: 10px;
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .parameter-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .parameter-table th {
            background-color: #2d3341;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #3a4151;
        }
        
        .parameter-table td {
            padding: 10px;
            border-bottom: 1px solid #3a4151;
        }
        
        .required {
            color: var(--swagger-danger);
        }
        
        .footer {
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
            border-top: 1px solid var(--swagger-secondary);
            color: #a0a0a0;
            font-size: 0.9rem;
        }
        
        .logo {
            font-size: 2rem;
            color: var(--swagger-primary);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="swagger-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="api-title">Pokemon API</h1>
                    <div class="d-flex align-items-center">
                        <span class="api-version">v1.0.0</span>
                        <span class="ms-3">REST API for listing Pokémon (via PokeAPI), viewing details, and managing favorites.</span>
                    </div>
                </div>
                <div class="text-end">
                    <div class="server-badge">
                        <i class="fas fa-server me-2"></i>Production: https://api.pokemon-app.akhyarazamta.com
                    </div>
                    <div class="server-badge mt-2">
                        <i class="fas fa-laptop-code me-2"></i>Local: http://localhost:8000
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-3">
                <div class="sticky-top" style="top: 20px;">
                    <h5>API Endpoints</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link text-light" href="#system">
                                <i class="fas fa-cogs me-2"></i>System
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link text-light" href="#pokemons">
                                <i class="fas fa-dragon me-2"></i>Pokemons
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link text-light" href="#favorites">
                                <i class="fas fa-star me-2"></i>Favorites
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="#bonus">
                                <i class="fas fa-coins me-2"></i>Bonus
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <h2 class="section-title" id="system">System</h2>
                
                <!-- Test API Connectivity -->
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <div class="d-flex align-items-center">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/test</span>
                            <span class="endpoint-summary">Test API connectivity</span>
                        </div>
                        <div class="mt-2">
                            <span class="tag-badge">System</span>
                        </div>
                    </div>
                    <div class="endpoint-body">
                        <p>Check if the API is running correctly.</p>
                        
                        <h6>Responses</h6>
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-200">200</span>
                            <span>API is working</span>
                        </div>
                        
                        <div class="example-box">
                            <div class="schema-key">{</div>
                            <div class="schema-property"><span class="schema-key">"message"</span>: <span class="schema-type">"Pokemon API is working!"</span></div>
                            <div class="schema-key">}</div>
                        </div>
                    </div>
                </div>
                
                <h2 class="section-title" id="pokemons">Pokemons</h2>
                
                <!-- Get Pokemon List -->
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <div class="d-flex align-items-center">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/pokemon</span>
                            <span class="endpoint-summary">Get paginated list of Pokemons from PokeAPI</span>
                        </div>
                        <div class="mt-2">
                            <span class="tag-badge">Pokemons</span>
                        </div>
                    </div>
                    <div class="endpoint-body">
                        <p>Retrieve a paginated list of Pokémon from the PokeAPI.</p>
                        
                        <h6>Parameters</h6>
                        <table class="parameter-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>page</td>
                                    <td>query</td>
                                    <td>integer</td>
                                    <td>No</td>
                                    <td>Page number (1-based), default: 1</td>
                                </tr>
                                <tr>
                                    <td>limit</td>
                                    <td>query</td>
                                    <td>integer</td>
                                    <td>No</td>
                                    <td>Items per page (max 100), default: 20</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h6>Responses</h6>
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-200">200</span>
                            <span>List of pokemons retrieved successfully</span>
                        </div>
                        
                        <div class="example-box">
                            <div class="schema-key">{</div>
                            <div class="schema-property"><span class="schema-key">"data"</span>: [</div>
                            <div class="schema-property" style="margin-left: 40px">{</div>
                            <div class="schema-property" style="margin-left: 60px"><span class="schema-key">"name"</span>: <span class="schema-type">"bulbasaur"</span>,</div>
                            <div class="schema-property" style="margin-left: 60px"><span class="schema-key">"url"</span>: <span class="schema-type">"https://pokeapi.co/api/v2/pokemon/1/"</span></div>
                            <div class="schema-property" style="margin-left: 40px">},</div>
                            <div class="schema-property" style="margin-left: 40px">{</div>
                            <div class="schema-property" style="margin-left: 60px"><span class="schema-key">"name"</span>: <span class="schema-type">"charmander"</span>,</div>
                            <div class="schema-property" style="margin-left: 60px"><span class="schema-key">"url"</span>: <span class="schema-type">"https://pokeapi.co/api/v2/pokemon/4/"</span></div>
                            <div class="schema-property" style="margin-left: 40px">}</div>
                            <div class="schema-property" style="margin-left: 20px">],</div>
                            <div class="schema-property"><span class="schema-key">"pagination"</span>: {</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"current_page"</span>: <span class="schema-type">1</span>,</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"total"</span>: <span class="schema-type">1118</span>,</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"per_page"</span>: <span class="schema-type">20</span>,</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"last_page"</span>: <span class="schema-type">56</span></div>
                            <div class="schema-property" style="margin-left: 20px">}</div>
                            <div class="schema-key">}</div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3 mt-4">
                            <span class="response-badge response-400">400</span>
                            <span>Invalid query parameters</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-500">500</span>
                            <span>Server error when fetching from PokeAPI</span>
                        </div>
                    </div>
                </div>
                
                <!-- Get Pokemon Detail -->
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <div class="d-flex align-items-center">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/pokemon/{id}</span>
                            <span class="endpoint-summary">Get Pokemon detail by ID or name</span>
                        </div>
                        <div class="mt-2">
                            <span class="tag-badge">Pokemons</span>
                        </div>
                    </div>
                    <div class="endpoint-body">
                        <p>Retrieve detailed information about a specific Pokémon.</p>
                        
                        <h6>Parameters</h6>
                        <table class="parameter-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>id</td>
                                    <td>path</td>
                                    <td>string</td>
                                    <td class="required">Yes</td>
                                    <td>Pokemon ID or name (e.g., "pikachu")</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h6>Responses</h6>
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-200">200</span>
                            <span>Pokemon detail retrieved successfully</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-400">400</span>
                            <span>Invalid Pokemon identifier</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-404">404</span>
                            <span>Pokemon not found</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-500">500</span>
                            <span>Internal server error</span>
                        </div>
                    </div>
                </div>
                
                <h2 class="section-title" id="favorites">Favorites</h2>
                
                <!-- Toggle Favorite -->
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <div class="d-flex align-items-center">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/pokemon/{id}/favorite</span>
                            <span class="endpoint-summary">Toggle favorite status for a Pokemon</span>
                        </div>
                        <div class="mt-2">
                            <span class="tag-badge">Favorites</span>
                        </div>
                    </div>
                    <div class="endpoint-body">
                        <p>Add or remove a Pokémon from favorites.</p>
                        
                        <h6>Parameters</h6>
                        <table class="parameter-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Required</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>id</td>
                                    <td>path</td>
                                    <td>string</td>
                                    <td class="required">Yes</td>
                                    <td>Pokemon ID or name</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h6>Responses</h6>
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-200">200</span>
                            <span>Favorite status toggled successfully</span>
                        </div>
                        
                        <div class="example-box">
                            <div class="schema-key">{</div>
                            <div class="schema-property"><span class="schema-key">"message"</span>: <span class="schema-type">"Pokemon added to favorites"</span>,</div>
                            <div class="schema-property"><span class="schema-key">"is_favorite"</span>: <span class="schema-type">true</span>,</div>
                            <div class="schema-property"><span class="schema-key">"data"</span>: {</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"id"</span>: <span class="schema-type">12</span>,</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"pokeapi_id"</span>: <span class="schema-type">1</span>,</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"name"</span>: <span class="schema-type">"bulbasaur"</span>,</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"types"</span>: [<span class="schema-type">"grass"</span>, <span class="schema-type">"poison"</span>],</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"abilities"</span>: [</div>
                            <div class="schema-property" style="margin-left: 40px">{</div>
                            <div class="schema-property" style="margin-left: 60px"><span class="schema-key">"name"</span>: <span class="schema-type">"overgrow"</span>,</div>
                            <div class="schema-property" style="margin-left: 60px"><span class="schema-key">"is_hidden"</span>: <span class="schema-type">false</span></div>
                            <div class="schema-property" style="margin-left: 40px">}</div>
                            <div class="schema-property" style="margin-left: 20px">],</div>
                            <div class="schema-property" style="margin-left: 20px"><span class="schema-key">"sprite"</span>: <span class="schema-type">"https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/1.png"</span></div>
                            <div class="schema-property" style="margin-left: 20px">}</div>
                            <div class="schema-key">}</div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3 mt-4">
                            <span class="response-badge response-400">400</span>
                            <span>Invalid Pokemon identifier</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-404">404</span>
                            <span>Pokemon not found</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-500">500</span>
                            <span>Internal server error</span>
                        </div>
                    </div>
                </div>
                
                <!-- Get All Favorites -->
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <div class="d-flex align-items-center">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/favorites</span>
                            <span class="endpoint-summary">Get all favorite pokemons</span>
                        </div>
                        <div class="mt-2">
                            <span class="tag-badge">Favorites</span>
                        </div>
                    </div>
                    <div class="endpoint-body">
                        <p>Retrieve all Pokémon marked as favorites.</p>
                        
                        <h6>Responses</h6>
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-200">200</span>
                            <span>Favorites list retrieved successfully</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-500">500</span>
                            <span>Internal server error</span>
                        </div>
                    </div>
                </div>
                
                <h2 class="section-title" id="bonus">Bonus</h2>
                
                <!-- Count Coins -->
                <div class="endpoint-card">
                    <div class="endpoint-header">
                        <div class="d-flex align-items-center">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/coins/count</span>
                            <span class="endpoint-summary">Count coin denominations (Bonus Task)</span>
                        </div>
                        <div class="mt-2">
                            <span class="tag-badge">Bonus</span>
                        </div>
                    </div>
                    <div class="endpoint-body">
                        <p>Count coin denominations from an array of coin values.</p>
                        
                        <h6>Request Body</h6>
                        <div class="schema-box">
                            <div class="schema-key">{</div>
                            <div class="schema-property"><span class="schema-key">"coins"</span>: [<span class="schema-type">50, 1000, 400, 50, 300, 1200, 1000, 25, 50, 45, 100</span>]</div>
                            <div class="schema-key">}</div>
                        </div>
                        
                        <h6>Responses</h6>
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-200">200</span>
                            <span>Coins counted successfully</span>
                        </div>
                        
                        <div class="example-box">
                            <div class="schema-key">{</div>
                            <div class="schema-property"><span class="schema-key">"coins"</span>: [<span class="schema-type">"3x 50"</span>, <span class="schema-type">"2x 1000"</span>, <span class="schema-type">"1x 400"</span>, <span class="schema-type">"1x 300"</span>, <span class="schema-type">"1x 1200"</span>, <span class="schema-type">"1x 25"</span>, <span class="schema-type">"1x 45"</span>, <span class="schema-type">"1x 100"</span>]</div>
                            <div class="schema-key">}</div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3 mt-4">
                            <span class="response-badge response-422">422</span>
                            <span>Validation error</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="response-badge response-500">500</span>
                            <span>Internal server error</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <div class="logo">
                <i class="fas fa-dragon"></i>
            </div>
            <p>Pokemon Favorites API v1.0.0</p>
            <p>REST API for listing Pokémon (via PokeAPI), viewing details, and managing favorites.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>