# Setting up deployment with GitHub Actions

This guide explains how to set up Continuous Integration (CI) and Continuous Deployment (CD) using GitHub Actions for your Fröjd-Bedrock project.

## Requirements

- An understanding of [CI/CD concepts](https://www.atlassian.com/continuous-delivery/)
- An understanding of [GitHub Actions](https://docs.github.com/en/actions)
- An understanding of [Ansible](https://www.ansible.com/overview/how-ansible-works)
- A fully generated Frojd-Bedrock project
- Access to two separate environments for staging and production
- Completed application provisioning on both environments using `provision.yml`
- A GitHub repository for your project

## Deployment Workflow

The GitHub Actions workflow is configured to:
- **Develop branch** → Automatically deploy to staging environment
- **Main branch** → Automatically deploy to production environment
- **Feature branches** → Build and test only, no deployment

## Setup Guide

### 1. Generate SSH Keys

Create SSH keys for deployment access to your servers:

```bash
# Generate key for staging environment
ssh-keygen -m PEM -t rsa -b 4096 -C "deploy@github-actions" -f stage.example.com

# Generate key for production environment
ssh-keygen -m PEM -t rsa -b 4096 -C "deploy@github-actions" -f example.com
```

**Important:** Leave the passphrase empty when prompted.

### 2. Add Public Keys to Servers

Send the public keys to your hosting provider or add them to the deploy user's `~/.ssh/authorized_keys` file on each server:
- `stage.example.com.pub` → Staging server
- `example.com.pub` → Production server

### 3. Configure GitHub Repository Secrets

In your GitHub repository, go to **Settings** → **Secrets and variables** → **Actions** and add:

#### Repository Secrets
- `ACF_PRO_KEY`: Your Advanced Custom Fields Pro license key
- `SSH_PRIVATE_KEY`: The private SSH key content (use the same key for both environments or configure environment-specific keys)

To copy the private key:
```bash
# On macOS
cat stage.example.com | pbcopy

# On Linux
cat stage.example.com | xclip -selection clipboard
```

### 4. Configure GitHub Environments

Create two environments in **Settings** → **Environments**:

#### Stage Environment
- Name: `stage`
- Protection rules: None (optional)
- Environment secrets: Can override repository secrets if needed

#### Prod Environment
- Name: `prod`
- Protection rules:
  - Required reviewers (recommended)
  - Restrict deployment to main branch only
- Environment secrets: Can override repository secrets if needed

### 5. Update Ansible Configuration

Ensure your Ansible inventory files are correctly configured:

- `deploy/stages/stage.yml` - Staging server details
- `deploy/stages/prod.yml` - Production server details

### 6. Push and Deploy

Once configured:
1. Push to `develop` branch to deploy to staging
2. Push/merge to `main` branch to deploy to production

## Workflow Features

### Parallel Build Jobs
The workflow runs three jobs in parallel for faster deployments:
- **Composer**: Installs PHP dependencies
- **Frontend**: Builds Vite assets
- **Deploy**: Runs Ansible playbooks after artifacts are ready

### Caching
- Composer dependencies are cached based on `composer.lock`
- NPM dependencies are cached based on `package-lock.json`

### Artifacts
Build artifacts are stored temporarily and passed between jobs to ensure consistency.

### Dependabot Integration
Automated dependency updates are configured for:
- NPM packages (weekly)
- Composer packages (weekly)
- Python packages for deployment scripts (monthly)

## Monitoring Deployments

View deployment status and logs:
1. Go to the **Actions** tab in your GitHub repository
2. Click on a workflow run to see detailed logs
3. Check the environment deployments in the **Deployments** section

## Security Notes

- Delete local SSH key files after setup for security
- Use GitHub's environment protection rules for production
- Regularly rotate SSH keys and update secrets
- Consider using GitHub's OIDC provider for keyless deployments (advanced)

## Troubleshooting

### SSH Connection Failed
- Verify the SSH key is correctly added to the server
- Check that the server hostname in Ansible inventory matches the actual server
- Ensure GitHub Actions IP ranges are not blocked by your server firewall

### ACF Pro Authentication Failed
- Verify the `ACF_PRO_KEY` secret is correctly set
- Check that the license key is valid and active

### Build Failures
- Check the workflow logs for specific error messages
- Ensure all required files are committed to the repository
- Verify that `composer.json` and `package.json` are valid

## Migration from CircleCI

If migrating from CircleCI:
1. Remove the `.circleci` directory
2. Cancel any active CircleCI builds
3. Remove CircleCI webhook from GitHub repository settings
4. Archive or delete CircleCI project