# TODO: Fix Render Vite Build Failure

## Plan Steps:
- [x] 1. Edit render.yaml: Add nodeVersion: "20", change to npm ci instead of yarn install.
- [x] 2a. Fix axios: Moved to dependencies in package.json.
- [ ] 2b. Test local: npm ci && npm run build.
- [ ] 3. User redeploys on Render and checks logs.
- [ ] 4. If issues, investigate Tailwind v4 or full logs.
