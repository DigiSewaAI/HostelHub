#!/bin/bash
set -e

echo "Fixing Apache MPM configuration..."

# 1. FIRST, REMOVE ALL MPM MODULE LOAD FILES
rm -f /etc/apache2/mods-enabled/mpm_*.load
rm -f /etc/apache2/mods-enabled/mpm_*.conf

# 2. ENABLE ONLY PREFORK MPM
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# 3. VERIFY ONLY ONE MPM IS ENABLED
echo "Enabled MPM modules:"
ls -la /etc/apache2/mods-enabled/mpm_* 2>/dev/null || echo "No MPM modules found (good!)"

# 4. RUN DEPLOYMENT SCRIPT
echo "Starting HostelHub deployment..."
exec /usr/local/bin/safe_deploy.sh