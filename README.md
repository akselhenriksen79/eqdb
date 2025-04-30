# EverQuest Item Database

A simple web application to browse EverQuest items.

## Deployment Instructions

### Setting up GitHub Secrets

1. Go to your GitHub repository
2. Click on "Settings" > "Secrets and variables" > "Actions"
3. Add the following secrets:
   - `FTP_USERNAME`: Your InfinityFree FTP username
   - `FTP_PASSWORD`: Your InfinityFree FTP password

### InfinityFree Setup

1. Make sure your InfinityFree account is set up
2. Upload the `everquest_items.db` file manually to your hosting account
3. Ensure PHP has SQLite3 extension enabled (InfinityFree should have this)

### Deployment

The GitHub Action will automatically deploy your code when you push to the main branch.