# Regression GraphQL

Ce dossier contient un kit simple de non-regression pour verifier que le plugin unique expose toujours les champs et mutations critiques.

## Prerequis

- WordPress avec `WPGraphQL Unified` active
- Endpoint GraphQL disponible (ex: `/graphql`)

## Execution (PowerShell)

```powershell
php .\tests\regression\verify-legacy-structure.php
php .\tests\regression\run-regression.php http://localhost/graphql
```

## Jeu de tests

- `queries/introspection-critical.graphql`
- `queries/auth-mutations.graphql`
- `queries/woo-root-fields.graphql`
- `queries/modules-critical.graphql`
- `queries/pageinfo-total.graphql`
