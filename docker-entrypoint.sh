#!/bin/bash
set -e

# 1. FIX THE APACHE MPM ERROR
echo "Fixing Apache MPM configuration..."
a2dismod mpm_event 2>/dev/null || true
a2dismod mpm_worker 2>/dev/null || true
a2dismod mpm_prefork 2>/dev/null || true
a2enmod mpm_prefork

# 2. RUN YOUR ORIGINAL DEPLOYMENT SCRIPT
echo "Starting HostelHub deployment..."
exec /usr/local/bin/safe_deploy.sh