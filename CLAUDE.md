# wp-soli-admin-theme

WordPress theme for `admin.soli.nl` - the central administration and identity provider for Muziekvereniging Soli.

## Purpose

This site serves two main functions:

1. **OIDC Identity Provider** - Provides OpenID Connect authentication for other Soli WordPress sites
2. **Administration Portal** - Centralized data management for the organization

## Theme Responsibilities

The theme itself is minimal and focused:

- **Authentication Gate**: Redirects all non-authenticated users to `wp-login.php`
- **User Dashboard**: Displays a single page with user data for logged-in users

All complex functionality (OIDC, data management) is handled by the companion `wp-soli-admin-plugin`.

## Architecture

```
admin.soli.nl
├── wp-soli-admin-theme (this repo) - Frontend/display layer
└── wp-soli-admin-plugin           - Business logic, OIDC, data structures
```

## Development Guidelines

### WordPress Version

Target the latest stable WordPress version. Use modern WordPress APIs and patterns.

### Coding Standards

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- Use WordPress escaping functions (`esc_html()`, `esc_attr()`, `esc_url()`, etc.)
- Sanitize all input, escape all output
- Prefix all functions, hooks, and global variables with `soli_admin_`

### Theme Structure

```
wp-soli-admin-theme/
├── functions.php      # Theme setup, hooks, authentication redirect
├── style.css          # Theme metadata and styles
├── index.php          # Main template (user dashboard)
├── screenshot.png     # Theme screenshot (optional)
└── assets/            # CSS, JS, images
```

### Security

- Never trust user input
- Use nonces for form submissions
- Validate user capabilities before displaying sensitive data
- Rely on `wp-soli-admin-plugin` for authentication/authorization logic

### Template Hierarchy

Since this is a single-purpose theme:
- `index.php` handles the logged-in user dashboard
- Authentication redirect happens in `functions.php` via `template_redirect` hook

## Git Workflow

- `main` branch is the primary branch
- Create feature branches for new work
- Keep commits focused and descriptive

## Related Repositories

- `wp-soli-admin-plugin` - Companion plugin handling OIDC and data management