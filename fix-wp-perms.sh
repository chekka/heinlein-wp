#!/bin/bash
#
# fix-wp-perms.sh 1.02
#
# This script configures WordPress file permissions based on recommendations
# from http://codex.wordpress.org/Hardening_WordPress#File_permissions
#
# CALL: 
# ./fix-wp-perms.sh HTTPDOCS_ROOT WP_OWNER
#
# 1.01: Initial release 
# 1.02: Added some safety checks
#
# Author: Michael Conigliaro <mike [at] conigliaro [dot] org>
# (MOD)2016 Modified by <h_schneider [at] marketmix [dot] com>
#

# Setup.start
#
WP_ROOT=$1 # Wordpress root directory
WP_OWNER=$2 # Wordpress owner
WP_GROUP=www-data # Wordpress group (Default for HostEurope Root Server)
#
# Setup.end

if [ $# -ne 2 ]; then
 echo "This script expects 2 arguments instead of $#"
 echo
 echo "USAGE:" 
 echo "fix-wp-perms.sh wordpress_root_folder owner"
 exit 10
fi

if [ ! -d $1 ]; then
 echo "ABORTED: The Wordpress root folder '$1' doesn't exist!"
 exit 10
fi

# Reset to safe defaults
#
echo "Setting permissions to safe defaults - this can take a while ..."
find ${WP_ROOT} -exec chown ${WP_OWNER}:${WP_GROUP} {} \;
find ${WP_ROOT} -type d -exec chmod 755 {} \;
find ${WP_ROOT} -type f -exec chmod 644 {} \;

# Allow wordpress to manage wp-config.php (but prevent world access)
#
echo "Procesing wp-config ..."
chgrp ${WS_GROUP} ${WP_ROOT}/wp-config.php
chmod 660 ${WP_ROOT}/wp-config.php

# Allow wordpress to manage wp-content
#
echo "Processing wp-content - this can take a while ..."
find ${WP_ROOT}/wp-content -exec chgrp ${WS_GROUP} {} \;
find ${WP_ROOT}/wp-content -type d -exec chmod 775 {} \;
find ${WP_ROOT}/wp-content -type f -exec chmod 664 {} \;

echo "DONE!"
exit 0